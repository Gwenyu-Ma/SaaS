<?php

use Lib\Model\Client;
use Lib\Store\Mysql as MC;
use \Lib\Model\RedisDataManager;
use Lib\Store\MysqlWriteCluster as MWC;
use Lib\Model\Org;
use System\DateTime;
use Lib\Util\DT\DTList;

class EnterpriseModel
{
    public static function getEids()
    {
        MC::clean();
        $rows = MC::getAll('SELECT EID,UserID FROM esm_user WHERE Status=1');
        return $rows;
    }

    public function initSys(){
        $eidRows=EnterpriseModel::getEids();
        foreach([['EID'=>'1234567890123456','UserID'=>'9999']] as $eidRow) {
            $eid = $eidRow['EID'];
            $userid = $eidRow['UserID'];
            $this->initOneEnterprise($eid,$userid);
        }
    }

    public function initOneEnterprise($eid,$userid){
        RedisDataManager::setEPOffLineTime($eid,$eid,$eid);
        $down = new DownloaderModel();
        $down->addEID($eid);

        MWC::setEID($eid);
        MWC::exec("CALL del_log_table('$eid');");

//        $this->afterLogin($eid,$userid);
    }
    public function afterLogin($eid, $uid)
    {
        $msg = new MessagerModel();
        $ok = $msg->addSubscriber("b:$eid:admins:$uid", json_encode([
            "rs:welcome:$eid:admins:$uid",
            "b:$eid:pf:ep:new",
            "b:$eid:pf:ep:uninst",
        ]));
        if(is_string($ok)){
            Log::add('ERROR', ['response'=>$ok]);
        }

        $ok = $msg->makeMsg(json_encode(["rs:welcome:$eid:admins:$uid"]), '       欢迎加入瑞星安全云大家庭，瑞星安全云是全新一代SaaS模式管理、服务中心，使用专属于您的云中心，可以集中管理Windows、Linux、手机等各种平台的终端，并享受瑞星专家服务，帮您更好的防范、发现、解决安全问题，信息安全，一切尽在掌握。<br/><br/>添加新终端：<br/>直接下载“安全中心\终端部署情况”下专属于您中心的终端安装包并安装，安装后即可自动加入您的中心，接受您的管理。', '欢迎使用瑞星安全云');

        if(is_string($ok)){
            Log::add('ERROR', ['response'=>$ok]);
        }
        MC::exec(sprintf("update esm_user set LastLoginTime='%s' where UserID=?", date('Y-m-d H:i:s', time())), [$uid]);
    }

    public static function getOrgs($args)
    {
        $where='';
        $orderBy=' order by OName';
        $limit=' limit 0,10';
        $params=[];
        if($args){
            $where.="where 1=1";
            if(!empty($args['eid'])){
                $where.=' and e.EID=?';
                $params[]=$args['eid'];
            }
            if(!empty($args['oName'])){
                $where.=' and e.OName LIKE ?';
                $params[]='%'.$args['oName'].'%';
            }
            if(!empty($args['uName'])){
                $where.=' and u.UserName LIKE ?';
                $params[]='%'.$args['uName'].'%';
            }
        }

        $sqlTotal=sprintf('SELECT count(OID) FROM esm_organization e INNER JOIN esm_user u ON e.EID=u.EID %s',$where);
        $total=MC::getCell($sqlTotal,$params);
        if($total<0){
            return ['total'=>$total,'rows'=>[]];
        }
        if(!empty($args['paging']['sort'])){
            $orderBy= sprintf(' order by %s %s',$args['paging']['sort'],$args['paging']['order']==1? 'ASC':'DESC');
        }
        if(intval($args['paging']['limit'])>0 && intval($args['paging']['offset'])>=0){
            $limit=' limit ?,?';
            $params[]=intval($args['paging']['offset']);
            $params[]=intval($args['paging']['limit']);

        }
        $sql=sprintf('SELECT e.EID,e.OName,u.UserName,u.CreateTime,u.LastLoginTime,u.UType type FROM esm_organization e INNER JOIN esm_user u ON e.EID=u.EID %s %s %s',$where,$orderBy,$limit);

        $rows=MC::getAll($sql,$params);
        $rows=array_map(function($row){
            $eid=$row['EID'];
            $row['OnlineCount']=RedisDataManager::getClientOnlineCountByEID($eid);
            $row['ClientCount']=RedisDataManager::getClientCountByEID($eid);
            return $row;
        },$rows);

        return ['total'=>$total,'rows'=>$rows];
    }

    //获取企业logo
    public static function getLogo($eid)
    {
        return Org::getLogo($eid);
    }
    //企业存储情况
    public static function usedSpace($eid)
    {
        return Org::usedSpace($eid);
    }

    public static function getCountByEID()
    {
        $sql='SELECT UType,count(UType) count FROM esm_user GROUP BY UType';
        MC::clean();
        $rows = MC::getAll($sql);
        $result=[];
        foreach($rows as $row){
            $result[$row['UType']]=$row['count'];
        }
        return $result;
    }

    //获取新增的企业列表
    public static function getNewOrgs()
    {
        $sql='SELECT u.EID eid,o.OName oname,u.CreateTime createtime,u.UserName uname,o.OSize osize FROM esm_organization o inner join esm_user u on u.EID=o.EID where u.Status=1  ORDER BY u.CreateTime DESC limit 0,7';
        MC::clean();
        $rows = MC::getAll($sql);
        return $rows;
    }

    //每天新增企业数
    public static function getNewOrgTrend($top=7)
    {
        MC::clean();
        $day=24*3600;
        $beginDate=date('Y-m-d',time()-$top*$day);
        $endDate=date('Y-m-d',time()-$day);

        $rows = MC::getAll('select counttime,eidnum,homeeidnum,compeidnum from count_addeid where counttime>=? and counttime<=? ORDER BY countTime desc  LIMIT 0,?',[$beginDate,$endDate,$top]);
        for($index=1; $index<$top; $index++){
            $result[strtotime($beginDate)+$day*$index]=[
                    'time'=>date('Y-m-d',strtotime($beginDate)+$day*$index),
                    'ucount'=>0,
                    'ecount'=>0,
                    'total'=>0
            ];
        }
        foreach($rows as $row){

            $time=strtotime($row['counttime']);

            if(array_key_exists($time,$result)){
                $result[$time]=[
                    'time'=>$row['counttime'],
                    'ucount'=>$row['homeeidnum'],
                    'ecount'=>$row['compeidnum'],
                    'total'=>$row['eidnum']
                ];
            }
        }
        $bTime=time();
        $eTime=time()+$days;
        $orgResult=self::getCountByUType(date('Y-m-d',$bTime),date('Y-m-d',$eTime));
        $result[$time]=[
            'time'=>date('Y-m-d',$bTime),
            'ucount'=>$orgResult['uCount'],
            'ecount'=>$orgResult['eCount'],
            'total'=>$orgResult['uCount']+$orgResult['eCount']
        ];
        return array_values($result);
    }

    public static function getCountByUType($beginDate=null,$endDate=null)
    {
        $sqlWhere='';
        $rangeDate=[];
        if($beginDate!=null){
            $sqlWhere.=' AND u.CreateTime>=?';
            $rangeDate[]=$beginDate;
        }
        if($endDate!=null){
            $sqlWhere.=' AND u.CreateTime < ?';
            $rangeDate[]=$endDate;
        }
        $rowAll=MC::getAll(sprintf('SELECT count(o.EID) count,u.UType FROM esm_user u INNER JOIN esm_organization o ON u.EID=o.EID WHERE u.Status=1 %s group by u.UType',$sqlWhere),$rangeDate);
        $result=['uCount'=>0,'eCount'=>0];
        foreach($rowAll as $row){
            $row['UType']==1 ? $result['eCount']=$row['count']
                            : $result['uCount']=$row['count'];
        }
        return $result;
    }

    //企业大小统计
    public static function getOrgSizeStat($top=7)
    {
        MC::clean();
        $rows=MC::getAll('select e.*,o.OName,o.OSize,o.CreateTime as Time from count_eptotal e inner join esm_organization o on e.EID=o.EID where o.OName is not null order by sguidNum,Time desc limit 0,?',[$top]);
        return $rows;
    }
}