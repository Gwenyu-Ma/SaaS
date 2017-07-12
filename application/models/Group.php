<?php

use Lib\Model\RedisDataManager;
use Lib\Util\Common as UCommon;

class GroupModel
{
    public $result;

    public function __construct()
    {
        $this->result = array();
        $this->groupinfo = select_manage_collection('groupinfo');
        $this->epinfo = select_manage_collection('epinfo');
        $this->policyinfo = select_manage_collection('policyinfo');
    }

    /**
     * 添加一个组
     * @param $name 组名称
     * @param $desp 组描述
     * @return bool
     */
    public function insertGroup($eid, $name, $desp)
    {
        $ok = $this->AddGroup($name, $desp, $eid, 1);
        $isOk =is_bool($ok) && $ok;
        return $isOk ? ['msg' => '失败']:true;
    }

    /**
     * @param $id
     * @param $name
     * @param string $desp
     * @return bool
     */
    public function editGroup($eid, $id, $name, $desp = '')
    {
        //修改组名称
        $res = $this->groupinfo->findAndModify(
            array(
                'eid' => $eid,
                'id' => intval($id),
            ), array(
            '$set' => array(
                'groupname' => $name,
                'description' => $desp,
                'edate' => time(),
            )), [
                'groupname' => true
            ]
        );
        $msg = '成功';
        $isOk = true;
        if (empty(is_array($res)) || empty($res['groupname'])) {
            $msg = '修改组信息失败。';
            $isOk = false;
        }
        add_oplog(3, 2005,json_encode(['group'=>[['guid'=>$id,'name'=>$res['groupname']]]]), $res['groupname'], $name, $msg,sprintf('编辑组"%s"为"%s"成功',$res['groupname'],$name));
        if (!$isOk) {
            add_oplog(3, 2005,json_encode(['group'=>[['guid'=>$id,'name'=>$res['groupname']]]]), $res['groupname'], $name, $msg,sprintf('编辑组"%s"为"%s"失败',$res['groupname'],$name));
            return ['msg' => $msg];
        }
        //写kafka
        $data = [
            'eid' => $eid,
            'id' => intval($id),
            'groupname' => $name,
            'description' => $desp,
            'edate' => time(),
        ];
        $data['logtype'] = 'groupinfo';
        //"optype"的值可以为"i"代表插入，"u"代表修改,"d"代表删除
        $data['optype'] = 'i';
        UCommon::writeKafka($eid, $data);

        //修改客户端信息中的组名称
        $res = $this->epinfo->update(array(
            'eid' => $eid,
            'groupid' => intval($id),
        ), array(
            '$set' => array(
                'groupname' => $name,
            ),
        ), array(
            'multiple' => true,
        ));

        if (!(is_array($res) && $res['ok'] == 1)) {
            return ['msg' => '组名称修改成功，客户端所属组名称变更失败。'];
        }
        //写kafka
        $result = $this->epinfo->find([
            'eid' => $eid,
            'groupid' => intval($id),
        ], [
            'sguid' => true,
        ]);
        foreach ($result as $client) {
            $data = [
                'eid' => $eid,
                'sguid' => $client['sguid'],
                'groupname' => $name,
            ];
            $data['logtype'] = 'epinfo';
            $data['optype'] = 'u';
            UCommon::writeKafka($eid, $data);
        }
        return true;

    }

    /**
     * 删除组
     * 前提：默认组不能被删除
     *
     * 1.将该组下所有客户端移动到默认组下，并更新客户端策略版本
     * 2.删除该组
     * 3.删除该组的策略
     * 4.更新redis
     * @param [string] $groupId 组id
     * @param [string] $eid     企业id
     * @return bool/array 成功返回true
     */
    public function DelGroup($groupId, $eid,$gName)
    {
        $obj=json_encode(['group'=>[['guid'=>$groupId,'name'=>$gName]]]);
        $desc=sprintf('删除组%s',$gName);
        //系统默认组不可删除
        $groupType = $this->getGroupType($eid, $groupId);
        if ($groupType != 1) {
            add_oplog(4,2006,$obj,null,null,'系统默认组不可删除',$desc.'失败');
            return ['msg' => '系统默认组不可删除'];
        }
        //获取默认组
        $defaultGroup = $this->getDefaultGroupName($eid);
        if (empty($defaultGroup)) {
            add_oplog(4,2006,$obj,null,null,'找不到默认组',$desc.'失败');
            return ['msg' => '找不到默认组'];
        }

        //移动客户端到默认组
        $result = $this->MoveComputerByGroupId($groupId, $defaultGroup['id'], $defaultGroup['groupname'], $eid);
        if (is_array($result)) {
            add_oplog(4,2006,$obj,null,null,'删除组失败，移动客户端到默认组时发生错误。',$desc.'失败');
            return ['msg' => '删除组失败，移动客户端到默认组时发生错误。'];
        }

        //删除组
        if (!($this->deleteGroup($eid, $groupId))) {
            add_oplog(4,2006,$obj,null,null,"客户端已移动到【" . $defaultGroup['groupname'] . "】，删除组时发生错误",$desc.'失败');
            return ['msg' => "客户端已移动到【" . $defaultGroup['groupname'] . "】，删除组时发生错误"];

        }
        add_oplog(4,2006,$obj,null,null,'成功',$desc.'失败');
        //删除组策略
        if ((new PolicyModel())->removePolicys([$groupId], 1, $eid)) {
            //清理缓存策略
            RedisDataManager::initPolicy($eid, $groupId, 1);
        }
        return true;
    }

    /**
     * @param $clients 客户端 $clients[]
     * @param $groupId 目标组
     * @param $eid   eid
     * @return bool
     */
    public function MoveComputer($clients, $groupId, $eid,$objects)
    {
        $group = $this->getGroupName($groupId, $eid);
        $msg = '成功';
        $desc=sprintf('移动终端到%s',$group['groupname']);
        $isOk = true;
        if (empty($group)) {
            $msg = '目标组不存在';
            add_oplog(3, 2003,json_encode($objects), null, $group['groupname'], $msg,$desc.'失败');
            return ['msg' => $msg];
        }
        $result = $clientInfo = $this->epinfo->update(array(
            'eid' => $eid,
            'sguid' => array(
                '$in' => $clients,
            ),
        ), array(
            '$set' => array(
                'groupid' => intval($groupId),
                'pstamp' => Common::getMicroTime(),
                'groupname' => $group['groupname'],
            ),
        ), array(
            'multiple' => true,
        ));
        if (!(is_array($result) && $result['ok'] == 1)) {
            $msg = '移动客户端是发生错误。';
            $isOk = false;
        }

        add_oplog(3, 2003, json_encode($objects), null, $group['groupname'], $msg,$desc.'成功');
        if (!$isOk) return ['msg' => $msg];
        $isBlackGroup = strcasecmp($groupId, $this->getBlackGroupID($eid)) == 0;

        foreach ($clients as $sguid) {
            RedisDataManager::initEPInfo($eid, $groupId, $sguid);
            //写kafka
            UCommon::writeKafka($eid, [
                'eid' => $eid,
                'sguid' => $sguid,
                'groupid' => intval($groupId),
                //'pstamp' => Common::getMicroTime(),
                'groupname' => $group['groupname'],
                'logtype' => 'epinfo',
                'optype' => 'u',
            ]);
            if ($isBlackGroup) {
                UCommon::writeKafka($eid, [
                    'eid' => $eid,
                    'sguid' => $sguid,
                    'logtype' => 'epinfo',
                    'optype' => 'd',
                ]);
            }
        }
        return true;
    }

    /**
     * 根据组移动客户端
     * @param [int] $sgroupId [源组]
     * @param [int] $ogroupId [目标组]
     * @param [string] $eid   [企业id]
     * @return  [bool/array]  [<description>]
     */
    private function MoveComputerByGroupId($sgroupId, $ogroupId, $ogroupName, $eid)
    {
        //获取要删除的组
        $group = $this->getGroupName($sgroupId, $eid);
        if (empty($group)) {
            return ['msg' => '源组不存在。'];
        }

        if (strcasecmp($sgroupId, $ogroupId) == 0) {
            return true;
        }
        $clientArr = $this->epinfo->find([
            'eid' => $eid,
            'groupid' => intval($sgroupId),
        ], [
            'sguid' => true,
        ]);

        $result = $this->epinfo->update(array(
            'eid' => $eid,
            'groupid' => intval($sgroupId),
        ), array(
            '$set' => array(
                'groupid' => intval($ogroupId),
                'pstamp' => Common::getMicroTime(),
                'groupname' => $ogroupName,
            ),
        ), array(
            'multiple' => true,
        ));

        if (!(is_array($result) && $result['ok'] == 1)) {
            return ['msg' => '移动客户端失败'];
        }

        RedisDataManager::updateClientCMDVer($eid, $ogroupId, 2);
        //写kafka
        foreach ($clientArr as $client) {
            UCommon::writeKafka($eid, [
                'eid' => $eid,
                'sguid' => $client['sguid'],
                'groupid' => intval($ogroupId),
                //'pstamp' => Common::getMicroTime(),
                'groupname' => $ogroupName,
                'logtype' => 'epinfo',
                'optype' => 'u',
            ]);
        }
        return true;
    }

    /*
     *判断组名是否存在
     */
    public function IsAlreadySet($str_name, $eid)
    {

        $group = $this->groupinfo->findOne(
            array(
                'eid' => $eid,
                'groupname' => $str_name,
            ),
            array(
                'id' => true,
            ));

        return !empty($group);
    }

    /**
     * 获取组名称
     * @param  [int] $groupId [组id]
     * @param  [string] $eid     [企业id]
     * @return [array]          [组名称和id]
     */
    public function getGroupName($groupId, $eid)
    {
        return $this->groupinfo->findOne(array(
            'eid' => $eid,
            'id' => intval($groupId),
        ), array(
            'id' => true,
            'groupname' => true,
        ));
    }

    /*
     *获取一个组的信息
     */
    public function GetGroup($id, $eid)
    {

        $result = $this->groupinfo->findOne(
            array('id' => intval($id), 'eid' => $eid),
            array('id' => true, 'groupname' => true, 'description' => true, 'edate' => true, 'type' => true)
        );

        return $result;
    }

    /*
     *添加组
     */
    public function AddGroup($name, $desp, $eid, $type = 1)
    {
        //检查组名称
        $isExists = $this->IsAlreadySet($name, $eid);

        if ($isExists) {
            return false;
        }

        $id = intval(Common::getMicroTime());
        $desc=sprintf('添加组"%s"',$name);

        $obj=json_encode(['group'=>[['guid'=>$id,'name'=>$name]]]);
        $content = array(
            'eid' => $eid,
            'id' => $id,
            'groupname' => $name, //remove_xss($name),
            'description' => $desp, //remove_xss($desp),
            'edate' => time(),
            'type' => $type,
        );
        $okResult = $this->groupinfo->insert(
            $content
        );

        if (!(is_array($okResult) && isset($okResult['ok']) && $okResult['ok'] == 1)) {
            $this->groupinfo->remove([
                'eid' => $eid,
                'id' => $id,
            ]);
            add_oplog(2, 2004,$obj, null, null, '失败',$desc.'失败');

            return false;
        }
        add_oplog(2, 2004,$obj, null, null, '成功',$desc.'成功');

        //写kafka
        $data = $content;
        $data['logtype'] = 'groupinfo';
        //"optype"的值可以为"i"代表插入，"u"代表修改,"d"代表删除
        $data['optype'] = 'i';
        UCommon::writeKafka($eid, $data);
        //policy
        $policy_model = new PolicyModel();

        switch ($type) {
            case 0: //默认组
                $bool_result = $policy_model->addDefaultGroupPolicy($eid, $id);
                break;
            case 2: //黑名单
                return $content;
            default:
                $bool_result = $policy_model->addDefaultPolicys($eid, $id);
                break;
        }

        if (!$bool_result) {
            return false;
        }

        return $content;
    }

    /**
     * @param $groupid init
     * @param $args array
     * @param $eid
     * @return array|bool|null
     */
    public function GetGroupComputer($groupid, $args, $eid)
    {
        $offset = !empty($args['offset']) ? intval($args['offset']) : 0;
        $limit = !empty($args['limit']) ? intval($args['limit']) : 0;

        $res = $this->getGroupComputers($eid, $groupid, $args);

        if ($limit > 0) {
            $res = $res->skip($offset)->limit($limit);
        }
        $rows = Common::mongoResultToArray($res);
        $rows = array_map(function ($client) {
            $client['mac'] = UCommon::mac($client['mac']);
            $client['os'] = UCommon::os($client['os']);
            //onlinestate  -1:查询所有，0：离线，1：在线，2：卸载  onlinestate只取redis中的，先判断unset值
            $client['onlinestate'] = $client['unset'] == 1 ? 2 : $this->getClientOnlineState($client['eid'], $client['sguid'], $client['systype']);
            return $client;
        }, $rows);
        return [
            'total' => $res->count(),
            'rows' => $rows,
        ];
    }

    /**
     * 获取客户端在线状态
     * @param  [string] $eid     [企业id]
     * @param  [string] $sguid   [客户端id]
     * @param  [string] $systype [客户端系统类型，可为空，为空时则安装sguid查询]
     * @return [int]          [0：离线；1：在线]
     */
    public function getClientOnlineState($eid, $sguid, $systype = null, $unset = null)
    {
        if (empty($systype) || empty($unset)) {
            $client = $this->epinfo->findOne([
                'eid' => $eid,
                'sguid' => $sguid,
            ], ['systype' => true, 'unset' => true]);
            $systype = empty($client) ? '' : $client['systype'];
            $unset = empty($client) ? '' : $client['unset'];
        }
        $lastTime = RedisDataManager::getEpLastlogintime($eid, $systype, $sguid);

        return $unset == 1 ? 2 : (!empty($lastTime) && $lastTime >= time() ? 1 : 0);
    }

    /**获取客户端名称
     * @param $sguid
     * @param $eid
     * @return array|bool|string
     */
    public function GetComputerNameinfo($sguid, $eid)
    {

        $res = Common::mongoResultToArray($this->epinfo->find(array('eid' => $eid, 'sguid' => $sguid)));
        $result = Common::formDate($res);
        return $result;
    }

    /*
     * sort: 用哪个字段排序
     * order: asc 正序 desc 倒序
     *offset 偏移量
     * limit 要查多少条
     */

    public function GroupList($args, $eid)
    {
        //添加分页
        $sort = !empty($args['sort']) ? $args['sort'] : null;
        $order = (!empty($args['order']) && $args['order'] === 'desc') ? -1 : 1;
        $offset = !empty($args['offset']) ? intval($args['offset']) : 0;
        $limit = !empty($args['limit']) ? intval($args['limit']) : 0;

        $data = [];
        $itrt = $this->groupinfo->find(array('eid' => $eid));
        $total = $itrt->count();
        if ($total === 0) {
            return null;
        }
        $data['total'] = $total;

        if (!empty($sort)) {
            $itrt->sort([$sort => $order]);
        }
        if ($limit > 0) {
            $itrt = $itrt->skip($offset)->limit($limit);
        }

        $result = array_values((iterator_to_array($itrt)));
        $data['rows'] = $result;
        return $data;
    }

    /**
     * @param $eid
     * @return array|null
     * 左侧组列表内容
     */
    public function GroupListAll($eid,$productIds)
    {
        $data = [];

        $where=['eid'=>$eid];
        $itrt = $this->groupinfo->find($where);
        $total = $itrt->count();
        if ($total === 0) {
            return null;
        }
        $data['total'] = $total;
        $result = $itrt;
        foreach ($result as $key => $group) {
            $group = array_merge($group, $this->getClinetOnlineStateOneGroup($eid, $group['id'],$productIds));
            $data['rows'][] = $group;
        }
        $data['rows'][] = array_merge(['id' => -1], $this->getClinetOnlineStateOneGroup($eid, -1));
        return $data;
    }

    /**
     *
     * 删除组信息
     */
    public function deleteGroup($eid, $id)
    {
        $result = $this->groupinfo->remove(array('id' => intval($id), 'eid' => $eid));
        if (is_array($result) && $result['ok'] == 1) {
            UCommon::writeKafka($eid, [
                'eid' => $eid,
                'id' => intval($id),
                'logtype' => 'groupinfo',
                'optype' => 'd',
            ]);
            return true;
        }
        return false;
    }

    /**
     * 获取默认组名称
     * @param  [string] $eid [企业id]
     * @return [array]      [默认组名称和id]
     */
    public function getDefaultGroupName($eid)
    {
        return $this->groupinfo->findOne(array(
            'eid' => $eid,
            'groupname' => '默认分组',
            'type' => 0,
        ), array(
            'id' => true,
            'groupname' => true,
        ));
    }

    /**
     * 获取组类型
     * @param  [string] $eid [企业id]
     * @param  [string] $id  [组id]
     * @return [int]      [组类型]
     */
    private function getGroupType($eid, $id)
    {
        $result = $this->groupinfo->findOne([
            'eid' => $eid,
            'id' => $id,
        ], [
            'type' => true,
        ]);
        return empty($result) ? 0 : intval($result['type']);
    }

    /**
     * 获取全网计算机组的id
     * @param $eid
     * @return int
     */
    public function getGlocbalGroupID($eid)
    {
        if (empty($eid)) {
            return 0;
        }
        $globalComputer = Common::mongoResultToArray($this->groupinfo->find(array('groupname' => '全网计算机', 'eid' => $eid)));
        if (!empty($globalComputer)) {
            foreach ($globalComputer as $k) {
                if (isset($k['id'])) {
                    return $k['id'];
                }
            }
        }
        return 0;
    }

    /**
     * @param $eid
     * @return array|bool
     */
    public function GetGroupInfo($eid)
    {
        if (empty($eid)) {
            return '';
        }
        $groupInfo = Common::mongoResultToArray($this->groupinfo->find(array('eid' => $eid)));
        return $groupInfo;
    }

    /**
     * @param $sguid 客户端id
     * @param $eid  eid
     * @return string 返回客户端string
     */
    public function getEpInfo($sguid, $eid)
    {
        $epinfoCollection = select_manage_collection('epinfo');
        $result = Common::mongoResultToArray($epinfoCollection->find(array('sguid' => $sguid, 'eid' => $eid)));
        if (!empty($result)) {
            foreach ($result as $k => $v) {
                unset($v["_id"]);
                return json_encode($v);
            }
        }
    }

    /**
     * 删除客户端
     * @param  [string] $clientId [客户端id]
     * @param  [string] $eid      [企业id]
     * @return [bool/array]       [description]
     */
    public function delClient($clients, $eid)
    {
        $result = $this->epinfo->remove(array('sguid' => ['$in' => $clients], 'eid' => $eid), ['justOne' => false]);
        if (is_array($result) && $result['ok'] == 1) {
            foreach ($clients as $sguid) {
                UCommon::writeKafka($eid, [
                    'eid' => $eid,
                    'sguid' => $sguid,
                    'logtype' => 'epinfo',
                    'optype' => 'd',
                ]);
            }
            return true;
        }
        return false;
    }

    /**
     * 获取客户基本信息
     * @param [string] $eid       [企业id]
     * @param [string] $objId     [企业/组/客户端 id]
     * @param [string] $groupType [企业:0/组:1/客户端:2]
     * @return   [MongoCursor] [客户端集合]
     */
    public function GetClientCursor($eid, $where, $fields)
    {
        $where['eid'] = $eid;
        return $this->epinfo->find($where, $fields);
    }

    /**
     * 根据条件查询epstate状态
     * @param $eid 企业id
     * @param $clients 客户端sguid列表，没有key值
     * @param $kvs key、vlue键值对，需要key
     * @return array|bool
     */
    public function getEpState($eid, $clients, $kvs)
    {
        $parms = array(
            'eid' => $eid,
            'sguid' => ['$in' => $clients],

        );
        //      print_r($kvs);
        if (!empty($kvs)) {
            foreach ($kvs as $k => $v) {
                $parms["kvs." . $k] = $v;
            }
        }
//        print_r($kvs);
//        print_r($parms);
        //print_r(json_encode($parms, true));

        $epstateCollection = select_log_collection('EpState');
        $result = Common::mongoResultToArray($epstateCollection->find($parms, array('eid' => true, 'sguid' => true, 'kvs' => true)));
        //var_dump($result);
        //print_r($result);
        if (!empty($result)) {
            return $result;
        } else {
            null;
        }
    }

    /**
     * [获取各个组的客户端在线情况统计]
     * @param  [string] $eid [企业id]
     * @return [type]      [description]
     */
    public function getClientOnlineStateByGroup($eid, $groupIds = null)
    {
        if (empty($groupIds)) {
            $groupArr = $this->groupinfo->find(['eid' => $eid], ['_id' => false, 'id' => true]);
            $groupIds = array_column(iterator_to_array($groupArr), 'id');
        }
        return $this->getClinetOnlineStateMultipleGroup($eid, $groupIds);
    }

    /**
     * 返回指定组的客户端在线统计
     * @param  [string] $eid      [企业id]
     * @param  [string] $groupIds [组id集合]
     * @return [array]           [每个组对应的客户端在线数和总数集合]
     */
    private function getClinetOnlineStateMultipleGroup($eid, $groupIds)
    {
        $result = [];
        foreach ($groupIds as $groupId) {
            if (!array_key_exists($groupId, $result)) {
                $result[$groupId] = $this->getClinetOnlineStateOneGroup($eid, $groupId);
            }
        }
        return $result;
    }

    /**
     * 返回单个指定组的客户端在线数统计
     * @param  [string] $eid     [企业id]
     * @param  [string] $groupId [组id]
     * @return [array]          ['online'=>0,'total'=>0]
     */
    private function getClinetOnlineStateOneGroup($eid, $groupId,$productIds)
    {
        $where = [
            'eid' => $eid,
        ];
        $isUnsetGroup = -1 == intval($groupId);
        if ($isUnsetGroup) {
            $where['unset'] = 1;
        } else {
            $where['unset'] = ['$ne' => 1];
            $where['groupid'] = intval($groupId);
        }
        if (!empty($productIds)) {
            $where['productinfo.guid'] = ['$in' => $productIds];
        }
        $clientArr = $this->epinfo->find($where, ['sguid' => true, 'systype' => true, 'unset' => true]);
        $total = $clientArr->count();
        $online = 0;
        if (!$isUnsetGroup) {
            foreach ($clientArr as $client) {
                $lastTime = RedisDataManager::getEpLastlogintime($eid, $client['systype'], $client['sguid']);
                $online += !empty($lastTime) && $lastTime >= time();
            }
        }
        return ['online' => $online, 'total' => $total];
    }

    public function getBlackGroupID($eid)
    {
        $group = select_manage_collection('groupinfo')->findOne(array(
            'eid' => $eid,
            'type' => 2,
        ), array(
            'id',
        ));
        return empty($group) ? '' : $group['id'];
    }

    /**
     * 根据条件查询客户端信息
     * 当组ID==eid时，查询当前企业的所有客户端信息
     * @param $groupid 组id
     * @param $args 查询条件
     * @param $eid 企业ID
     * @return mixed 返回mongo对象
     */
    private function getGroupComputers($eid, $groupid, $args)
    {
        $sort = !empty($args['sort']) ? $args['sort'] : 'name';
        $order = (!empty($args['order']) && $args['order'] === 'desc') ? -1 : 1;

        $where = array();
        $where['eid'] = $eid;
        $blackGID = $this->getBlackGroupID($eid);
        //如果查询全网计算机，调用本方法时，只需要指定groupid=0
        if ($groupid == -1) {
            $where['unset'] = 1;
        } else if ($groupid == -2) {
            $where['$or'] = [
                ['unset' => 1],
                ['groupid' => $blackGID]
            ];
        } else if ($groupid == 0 || strcasecmp($eid, $groupid) == 0) {
            $where['unset'] = ['$ne' => 1];
            $where['groupid'] = ['$ne' => $blackGID];
        } else if (strcasecmp($groupid, $blackGID) == 0) {
            $where['groupid'] = intval($groupid);
        } else {
            $where['unset'] = ['$ne' => 1];
            $where['groupid'] = intval($groupid);
        }
        if (isset($args['onlinestate']) && $args['onlinestate'] != -1) {
            //在线状态过滤
            $clientArr = Common::mongoResultToArray($this->GetClientCursor($eid, $where, ['_id' => false, 'sguid' => true, 'systype' => true]));

            $sguidArr = RedisDataManager::getEpOnline($eid);
            $onlinesguids = [];
            $offlinesguids = [];
            foreach ($clientArr as $client) {
                $key = $client['systype'] . ':' . $client['sguid'];
                $lastTime = $sguidArr[$key];
                if (!empty($lastTime) && $lastTime >= time()) {
                    $onlinesguids[] = $client['sguid'];
                } else {
                    $offlinesguids[] = $client['sguid'];
                }
            }
//            print_r($clientArr);
//            print_r($offlinesguids);
            $where = [
                'sguid' => [
                    '$in' => ($args['onlinestate'] == 1 ? $onlinesguids : $offlinesguids)
                ]
            ];

        }
        //var_dump($where);
        if (!empty($args['name'])) {
            $preg = "/" . $args['name'] . "/i";
            $regexObj = new MongoRegex($preg);
            $where['computername'] = $regexObj;
            //            $where['description'] = $regexObj;  ???
        }

        if (!empty($args['ip'])) {
            $preg = "/" . $args['ip'] . "/i";
            $regexObj = new MongoRegex($preg);
            $where['ip'] = $regexObj;
        }
        if (!empty($args['mac'])) {
            $preg = "/" . $args['mac'] . "/i";
            $regexObj = new MongoRegex($preg);
            $where['mac'] = $regexObj;
        }
        if (!empty($args['sys'])) {
            $preg = "/" . $args['sys'] . "/i";
            $regexObj = new MongoRegex($preg);
            $where['os'] = $regexObj;
        }
        if (!empty($args['version'])) {
            $preg = "/" . $args['version'] . "/i";
            $regexObj = new MongoRegex($preg);
            $where['version'] = $regexObj;
        }
        if (!empty($args['productIds'])) {
            $where['productinfo.guid'] = ['$in' => $args['productIds']];
        }
        //print_r($where);
        $res = $this->GetClientCursor($eid, $where, array(
            '_id' => false,
            'eid' => true,
            'sguid' => true,
            'groupid' => true,
            'ip' => true,
            'version' => true,
            'os' => true,
            'computername' => true,
            'username' => true,
            'memo' => true,
            'groupname' => true,
            'unset' => true,
            'mac' => true,
            'systype' => true,
            'edate' => true,
        ));
        return $res->sort(array(
            $sort => $order,
        ));
    }

    /**
     * 根据eid，groupid，客户端参数、状态参数 查询客户端和状态的结果集
     * @param $eid 企业ID
     * @param $args 客户端参数
     * @param $groupid 组ID
     * @param $kvs 状态参数
     */
    public function getComputersEpState($eid, $groupid, $args, $kvs, $columns)
    {
        //客户端结果集
        $clients = $this->getGroupComputers($eid, $groupid, $args);

        if (empty($clients)) {
            return null;
        }

        $sguids = array();
        foreach ($clients as $client) {
            array_push($sguids, $client['sguid']);
        }
        //根据客户端sguid查询状态结果集
        $epStates = $this->getEpState($eid, $sguids, $kvs);
        $clients = common::mongoResultToArray($clients);

        //返回合并后的结果集
        return $this->mergeComputersEpState($clients, $epStates, $columns);
    }

    /**
     * 合并客户端和状态结果集
     * @param $clients
     * @param $epStates
     * @return mixed
     */
    private function mergeComputersEpState($clients, $epStates, $columns)
    {
        //$columns = array('engver', 'sysmon', 'filemon', 'rfwurlmailscan', 'rfwurlxss');
        if (empty($clients) || empty($epStates)) {
            return null;
        }
        //print_r($epStates);

        foreach ($clients as $cKey => &$client) {
            //格式化mac
            $client['mac'] = UCommon::mac($client['mac']);
            $client['os'] = UCommon::os($client['os']);
            //添加客户端在线状态
            $client['onlinestate'] = $client['unset'] == 1 ? 2 : $this->getClientOnlineState($client['eid'], $client['sguid'], $client['systype']);

            $isExists = false;
            foreach ($epStates as $key => $ep) {
                if ($ep['eid'] === $client['eid'] && $ep['sguid'] === $client['sguid']) {
                    $isExists = true;
                    if (!empty($ep['kvs'])) {
                        //print_r($ep['kvs']);
                        foreach ($ep['kvs'] as $kvsvalue) {
                            foreach ($kvsvalue as $k => $v) {
                                $k = strtolower($k);
                                if (in_array($k, $columns)) {
                                    $client[$k] = $v;
                                }
                            }
                        }
                    }
                    unset($epStates[$key]);
                    break;
                }
            }
            if (!$isExists) {
                unset($clients[$cKey]);
            }
        }

        return array(
            "rows" => array_values($clients),
            "total" => count($clients),
        );
    }

    public static function getClientSGuidArr($eid, $objId, $groupType)
    {
        switch ($groupType) {
            case 0:
                $where = array(
                    'eid' => $objId,
                );
                break;
            case 1:
                $where = array(
                    'eid' => $eid,
                    'groupid' => intval($objId),
                );
                break;
            case 2:
                $where = array(
                    'eid' => $eid,
                    'sguid' => $objId,
                );
                break;
            default:
                return false;
        }
        $where['unset'] = ['$ne' => 1];

        $clientColl = select_manage_collection('epinfo')->find($where, array(
            'sguid' => true
        ));
        $clientArr = [];
        foreach ($clientColl as $client) {
            $clientArr[] = $client['sguid'];
        }
        return array_values($clientArr);
    }
}
