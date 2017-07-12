<?php
use Lib\Model\RedisDataManager;
use Lib\Util\Common as UCommon;
use \Lib\Store\MysqlCluster as MC;

class EpModel
{
    public function __construct()
    {
        $this->ep = select_manage_collection('epinfo');
        $this->plc = select_manage_collection('policyinfo');
        $this->pdt = select_manage_collection('product');
        $this->group = select_manage_collection('groupinfo');
    }

    public function getEP($eid, $sguid)
    {
        $ep = $this->ep->findOne(['eid' => $eid, 'sguid' => $sguid]);
        $model = new GroupModel();
        foreach ($ep['productinfo'] as &$v) {
            $pdt = $this->pdt->findOne(['guid' => $v['guid']]);
            $v['name'] = $pdt['name'];
            $v['codename'] = $pdt['codename'];
            $v['version'] = $pdt['version'];
        }
        $ep['onlinestate'] = $ep['unset'] == 1 ? 2 : $model->getClientOnlineState($eid, $sguid, $ep['systype']);
        return $ep;
    }

    public function setMemo($eid, $sguid, $memo,$oObj)
    {
        if (!is_array($sguid)) {
            $sguid = [$sguid];
        }
        $res = $this->ep->update(['eid' => $eid, 'sguid' => ['$in' => $sguid]], ['$set' => ['memo' => $memo]]);
        $object=[ep=>[['guid'=>$sguid,'name'=>$oObj['cName']]]];
        $desc=sprintf('修改终端%s备注为%s',$oObj,$memo);
        if (!(is_array($res) && $res['ok'] == 1)) {
            add_oplog(2,2007,$object,$oObj['oldMemo'],$memo,'失败',$desc.'失败');
            return false;
        }
        foreach ($sguid as $item) {
            RedisDataManager::updateEpMemo($eid, $item, $memo);
            UCommon::writeKafka($eid, [
                'eid' => $eid,
                'sguid' => $item,
                'memo' => $memo,
                'logtype' => 'epinfo',
                'optype' => 'u',
            ]);
        }
        add_oplog(2,2007,$object,$oObj['oldMemo'],$memo,'成功',$desc.'成功');
        return true;
    }

    /**
     * 获取客户端授权数
     * @param  [type] $eid [description]
     * @return [type]      [description]
     */
    public function clientAccreditStatus($eid)
    {

        $groupInfo = $this->group->findOne(['eid' => $eid, 'type' => 2], ['id' => true]);

        $linuxCount = $clientArr = $this->ep->find(['eid' => $eid, 'unset' => 0, 'systype' => 'linux', 'groupid' => ['$ne' => $groupInfo['id']]], ['sguid' => true])->count();

        $androidCount = $clientArr = $this->ep->find(['eid' => $eid, 'unset' => 0, 'systype' => 'android', 'groupid' => ['$ne' => $groupInfo['id']]], ['sguid' => true])->count();

        $winCount = $clientArr = $this->ep->find(['eid' => $eid, 'unset' => 0, 'systype' => 'windows', 'groupid' => ['$ne' => $groupInfo['id']]], ['sguid' => true])->count();

        return [
            "total" => 0,//$linuxCount + $androidCount + $winCount,
            "useCount" => $linuxCount + $androidCount + $winCount,
            "androidCount" => $androidCount,
            "linuxCount" => $linuxCount,
            "winCount" => $winCount,
        ];
    }

    public function delEP($eid, $sguids)
    {
        $result = $this->ep->remove(['eid' => $eid, 'sguid' => ['$in' => $sguids]]);
        foreach ($sguids as $sguid) {
            UCommon::writeKafka($eid, [
                'eid' => $eid,
                'sguid' => $sguid,
                'logtype' => 'epinfo',
                'optype' => 'd',
            ]);
        }

        return is_array($result) && $result['ok'] == 1;
    }

    //删除客户端
    public function removeEP($eid, $sguids)
    {
        //清理策略表
        (new PolicyModel())->removePolicys($sguids, 2, $eid);
        //清理命令表
        (new CmdModel())->removeCmds($sguids, 2, $eid);
        //清理redis缓存
        RedisDataManager::removeClient($sguids, $eid);
        //清理mysql
        foreach ($sguids as $sguid) {
            MC::$eid = $eid;
            MC:exec(sprintf("CALL drop_client_alldatas('%s','%s')", $eid, $sguid));
        }
        //清理epinfo表
        $this->delEP($eid, $sguids);
        return true;
    }

    public function getUnsetEPs()
    {
        $eps = $this->ep->find(['unset' => 1],['eid'=>true,'sguid' => true]);
        //$eps = Common::mongoResultToArray($eps);
        //print_r($eps);
        foreach ($eps as $v) {
            $eid = $v['eid'];
            $sguid = $v['sguid'];
            echo 'eid='.$eid.'   ';
            echo 'sguid='.$sguid.'<br />';
            MC::$eid = $eid;
            MC::exec("UPDATE epinfo_$eid SET unset=1 where eid='$eid' and sguid = '$sguid'");
        }
    }
}
