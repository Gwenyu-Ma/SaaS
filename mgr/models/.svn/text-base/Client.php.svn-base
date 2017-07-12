<?php

use \Lib\Store\MysqlCluster as MWC;
use \Lib\Store\Mysql as MC;
use \Lib\Model\RedisDataManager as RedisDM;
use \Lib\Model\Client;
use System\DateTime;
class ClientModel
{
    //初始化客户端操作系统
    public static function initClientOS($eid)
    {
        // $eidRows=EnterpriseModel::getEids();
        // //$eidRows=[['EID'=>'D05D6DE488005623']];
        // MWC::clean();
        // foreach($eidRows as $eidRow){
        //     $eid=$eidRow['EID'];
            //先清理数据
            RedisDM::delClient_hosstat_os($eid);
            MWC::clean();
            MWC::$eid=$eid;
            $rows = MWC::getAll(sprintf('select eid,sguid,os from epinfo_%s',$eid));
            foreach($rows as $client){
                $score=7;
                $filed='other';
                if(stripos($client['os'], 'Windows XP')!==false){
                    $filed="xp";
                    $score=1;
                }
                if(stripos($client['os'],'Windows 7')!==false){
                    $filed="win7";
                    $score=2;
                }
                if(stripos($client['os'],'Windows 8')!==false){
                    $filed="win8";
                    $score=3;
                }
                if(stripos($client['os'],'Android')!==false){
                    $filed="android";
                    $score=4;
                }
                if(stripos($client['os'],'openSUSE')!==false || stripos($client['os'],'RockyOS')!==false){
                    $filed="linux";
                    $score=5;
                }
                if(stripos($client['os'],'CentOS')!==false){
                    $filed="centos";
                    $score=6;
                }
                if(stripos($client['os'],'Windows 10')!==false){
                    $filed='win10';
                    $score=8;
                }
                RedisDM::addClientCountByOs($eid,$filed,1);
                RedisDM::addClientOs($client['eid'], $score, $client['sguid']);
            }
        // }
    }

    //初始化客户端位置
    public static function initClientLoc($eid)
    {
        // $eidRows=EnterpriseModel::getEids();
        // //$eidRows=[['EID'=>'D05D6DE488005623']];
        // MWC::clean();
        // foreach($eidRows as $eidRow){
        //     $eid=$eidRow['EID'];
            MWC::clean();
            MWC::$eid=$eid;
            $rows=MWC::getAll(sprintf('select sguid,lng,lat,date from AND_Loc_%s order by date desc limit 1',$eid));
            foreach($rows as $row){
                RedisDM::addClientLoc($eid,$row['sguid'],$row['lng'],$row['lat'],$row['date']);
            }
        // }
    }

    //初始化终端威胁
    public static function initClientW()
    {
        $date=date('Y-m-d',strtotime('-7 day'));
        $eidRows=EnterpriseModel::getEids();
        //$eidRows=[['EID'=>'D05D6DE488005623']];
        $index=7;
        while($index>0){
            $bDate=date('Y-m-d',strtotime('+'.($index).' day',strtotime($date)));
            $eDate=date('Y-m-d',strtotime('+'.($index+1).' day',strtotime($date)));
            $dRange=[':bDate'=>$bDate,':eDate'=>$eDate];
            $overTime=$index*24*3600;
            foreach($eidRows as $eidRow){
                $eid=$eidRow['EID'];
                RedisDM::delClientW($eid,date('Ymd',strtotime($bDate)));
                //wu
                self::initClientWv($eid,$dRange,$overTime);
                //wv
                self::initClientWu($eid,$dRange,$overTime);
                //wh
                self::initClientWh($eid,$dRange,$overTime);
                //wn
                self::initClientWn($eid,$dRange,$overTime);
                //wt
            }
            $index--;
        }
    }

    private static function initClientWv($eid,$dRange,$overTime)
    {
        $date=date('Ymd',strtotime($dRange[':bDate']));
        MWC::clean();
        MWC::$eid=$eid;
        $wrSguidRow=MWC::getAll(sprintf('select distinct sguid from XAV_ScanEvent_%s where time>=:bDate and time<:eDate',$eid),$dRange);
        foreach($wrSguidRow as $wrSguid){
            RedisDM::addClientWSguids($eid,'wv',$date,$wrSguid['sguid'],$overTime);
        }
    }
    private static function initClientWu($eid,$dRange,$overTime)
    {
        $date=date('Ymd',strtotime($dRange[':bDate']));
        MWC::clean();
        MWC::$eid=$eid;
        $wuSguidRow=MWC::getAll(sprintf('select distinct sguid from RFW_UrlInterceptLog_%s where time>=:bDate and time<:eDate',$eid),$dRange);
        $wuSguids=array_column($wuSguidRow,'sguid');
        //$wuSguids=['hello1','hello2','hello3'];
        foreach($wuSguids as $wuSguid){
            RedisDM::addClientWSguids($eid,'wu',$date,$wuSguid,$overTime);
        }
    }
    private static function initClientWh($eid,$dRange,$overTime)
    {
        $date=date('Ymd',strtotime($dRange[':bDate']));
        MWC::clean();
        MWC::$eid=$eid;
        $whSguidRow1=MWC::getAll(sprintf('select distinct sguid from AND_Phone_SpamPhone_%s where date>=:bDate and date<:eDate',$eid),$dRange);
        $whSguidRow2=MWC::getAll(sprintf('select distinct sguid from AND_Phone_SpamMsg_%s where date>=:bDate and date<:eDate',$eid),$dRange);
        $whSguids=array_unique(array_merge(array_column($whSguidRow1,'sguid'),array_column($whSguidRow2,'sguid')));
        //$whSguids=['hello1','hello2'];
        foreach($whSguids as $whsguid){
            RedisDM::addClientWSguids($eid,'wh',$date,$whsguid,$overTime);
        }
    }
    private static function initClientWn($eid,$dRange,$overTime)
    {
        $date=date('Ymd',strtotime($dRange[':bDate']));
        MWC::clean();
        MWC::$eid=$eid;
        $wnSguidRow1=MWC::getAll(sprintf('select distinct sguid from RFW_BrowsingAuditLog_%s where time>=:bDate and time<:eDate',$eid),$dRange);
        $wnSguidRow2=MWC::getAll(sprintf('select distinct sguid from RFW_NetProcAuditLog_%s where time>=:bDate and time<:eDate',$eid),$dRange);
        $wnSguidRow3=MWC::getAll(sprintf('select distinct sguid from RFW_SharedResAccessAuditLog_%s where time>=:bDate and time<:eDate',$eid),$dRange);
        $wnSguids=array_unique(array_merge(array_column($wnSguidRow1,'sguid'),array_column($wnSguidRow2,'sguid'),array_column($wnSguidRow3,'sguid')));
        //$wnSguids=['hello1','hello2','hello4','喝了lo'];
        foreach($wnSguids as $wnsguid){
            RedisDM::addClientWSguids($eid,'wn',$date,$wnsguid,$overTime);
        }
    }

    public static function getClientList($eid,$args,$columns)
    {
        $result=self::getClients($eid,$args,$columns);
        $result['rows']==array_map(function($row){
            $eid=$row['eid'];
            $row['onlinestate']=Client::getClientOnlineState($eid,$row['sguid'],$row['systype']);
            $row['activetime']=0;//ClientModel::getClientCount($eid);
            $row['lastime']=$row['edate'];
            return $row;
        },$result['rows']);
        return $result;
    }

    //获取客户端个数
    public static function getClientCount($eid)
    {
        try
        {
            MWC::clean();
            MWC::$eid=$eid;
            $eps=MWC::findAll("epinfo_".$eid);
            return count($eps);
        }
        catch(Exception $ex){

        }
    }

    public static function getClients($eid,$args,$columns)
    {
            $where='';
            $orderBy=' order by computername';
            $limit=' limit 0,10';
            $params=[];
            if($args){
                $where.="where 1=1";
                if(!empty($args['uName'])){
                    $where.=' and username LIKE ?';
                    $params[]='%'.$args['uName'].'%';
                }
                if(!empty($args['groupId'])){
                    $where.=' and groupid=?';
                    $params[]=$args['groupId'];
                }
                if(!empty($args['sKey'])&&!empty($args['sValue'])){
                    $where.=' and '.$args['sKey'].' LIKE ?';
                    $params[]='%'.$args['sValue'].'%';
                }

            }
            $columnStr=implode(',',$columns);
            MWC::clean();
            MWC::$eid=$eid;
            // echo sprintf('SELECT %s FROM epinfo_%s %s %s %s',$columnStr,$eid,$where,$orderBy,$limit);

            $total=MWC::getCell(sprintf('SELECT count(sguid) FROM epinfo_%s %s',$eid,$where),$params);
            if($total<0){
                return ['total'=>$total,'rows'=>[]];
            }
            if(!empty($args['paging']['sort'])){
                $orderBy=sprintf(' order by %s %s',$args['paging']['sort'],$args['paging']['order']==1? 'ASC':'DESC');
                // $params[]=$args['paging']['sort'];
                // $params[]=$args['paging']['order']==1? 'ASC':'DESC';
            }
            if(intval($args['paging']['limit'])>0 && intval($args['paging']['offset'])>=0){
                $limit=' limit ?,?';
                $params[]=intval($args['paging']['offset']);
                $params[]=intval($args['paging']['limit']);
            }
            // echo sprintf('SELECT %s FROM epinfo_%s %s %s %s',$columnStr,$eid,$where,$orderBy,$limit);
            // print_r($params);
            $rows=MWC::getAll(sprintf('SELECT %s FROM epinfo_%s %s %s %s',$columnStr,$eid,$where,$orderBy,$limit),$params);
            return ['total'=>$total,'rows'=>$rows];
    }

    //初始化客户端策略
    public static function initEPPolicy($eid,$sguid)
    {
        RedisDM::initPolicy($eid,$sguid,2);
    }

   //初始化客户端信息
    public static function initEPInfo($eid,$groupId,$sguid)
    {
        RedisDM::initEPInfo($eid,$groupId,$sguid);
    }

    //获取每日新增终端趋势
    public static function getNewClientTrend($top=7)
    {
        MC::clean();
        $rows=MC::getAll('select * from count_addep order by countTime limit 0,? ',[$top]);
        $epinfo=select_manage_collection('epinfo');
        $day=24*3600;
        $beginDate=date('Y-m-d',time()-$top*$day);
        $endDate=date('Y-m-d',time()-$day);
        for($index=1; $index<$top; $index++){
            $result[strtotime($beginDate)+$day*$index]=[
                    'time'=>date('Y-m-d',strtotime($beginDate)+$day*$index),
                    'win'=>0,
                    'linux'=>0,
                    'android'=>0
            ];
        }
        foreach($rows as $row){
            $time=strtotime($row['counttime']);
            if(array_key_exists($time,$result)){
                $result[$time]=[
                    'time'=>date('Y-m-d', $time),
                    'win'=>$row['winnum'],
                    'linux'=>$row['linuxnum'],
                    'android'=>$row['androidnum']
                ];
            }
        }

        $bTime=time();
        $eTime=time()+$days;
        $args=[
            'edate'=>[
                '$gte'=>$bTime,
                '$lt'=>$eTime
                ],
            'systype'=>'windows'
            ];
        $androidArgs = $linuxArgs = $args;
        $androidArgs['systype']='android';
        $linuxArgs['systype']='linux';
        $result[$bTime]=[
            'time'=>date('Y-m-d',$bTime),
            'win'=>$epinfo->count($args),
            'linux'=>$epinfo->count($linuxArgs),
            'android'=>$epinfo->count($androidArgs)
            ];
        return array_values($result);
    }

    //统计每分钟终端在线状态数量
    public static function getClientOnLineStatByTime()
    {
        MC::clean();
        $rows=MC::getAll('select * from count_lineep order by counttime desc limit 0,12');
        return $rows;
    }

    //按操作系统统计客户端个数
    public static function getClientOSTypeStat()
    {
        $result=EnterpriseModel::getCountByUType();
        return [
                'win'=> RedisDM::getSguidCountByOSType('winsguidnum'),
                'android'=> RedisDM::getSguidCountByOSType('androidsguidnum'),
                'linux'=> RedisDM::getSguidCountByOSType('linuxsguidnum'),
                'ucount'=>$result['uCount'],
                'ecount'=>$result['eCount']
            ];
    }
}