<?php

use \Lib\Store\MysqlCluster as MC;
use \Lib\Model\RedisDataManager as RedisDM;
class XavModel
{
    ///病毒统计
    public static function initXav($eid)
    {
        // $eidRows=EnterpriseModel::getEids();
        //$eidRows=[['EID'=>'D05D6DE488005623']];
        //$days=7;
        $date=date('Y-m-d',strtotime('-7 day'));
        // foreach($eidRows as $eidRow){
            // $eid=$eidRow['EID'];
            RedisDM::delXav($eid);
            $days=7;
            while($days>0){
                $bDate=date('Y-m-d',strtotime('+'.($days).' day',strtotime($date)));
                $eDate=date('Y-m-d',strtotime('+'.($days+1).' day',strtotime($date)));
                $dRange=[':bDate'=>$bDate,':eDate'=>$eDate];
                $overTime=$days*24*3600;
                    self::initXavTimesWithDateRange($eid,$dRange,$overTime);
                    self::initXavFileCountWithDateRange($eid,$dRange,$overTime);
                $days--;
            }            
        // }
    }

    //病毒次数统计
    private static function initXavTimesWithDateRange($eid,$dRange,$overTime)
    {
        $date=$dRange[':bDate'];
        MC::clean();
        MC::$eid=$eid;
        $resultRows=MC::getAll(sprintf('select virusclass,count(1) count from XAV_Virus_%s where findtime>=:bDate and findtime<:eDate group by virusclass',$eid),$dRange);
        foreach($resultRows as $resultRow){
            RedisDM::addXavTimes($eid,$resultRow['virusclass'],$date,$resultRow['count'],$overTime);
        }
    }

    //病毒文件统计
    private static function initXavFileCountWithDateRange($eid,$dRange,$overTime)
    {
        $date=$dRange[':bDate'];
        MC::clean();
        MC::$eid=$eid;
        $resultRows=MC::getAll(sprintf('select virusclass,count(distinct uniquevalue) count from XAV_Virus_%s where findtime>=:bDate and findtime<:eDate group by virusclass',$eid),$dRange);
        foreach($resultRows as $resultRow){
            RedisDM::addXavFileCount($eid,$resultRow['virusclass'],$date,$resultRow['count'],$overTime);
        }
    }

    //恶意网址拦截分类统计图
    public static function initRfwUrlByResult($eid)
    {
        // $eidRows=EnterpriseModel::getEids();
        //$eidRows=[['EID'=>'D05D6DE488005623']];
        $days=7;
        $date=date('Y-m-d',strtotime('-'.$days.' day'));
        MC::clean();
        while($days>0){
            $bDate=date('Y-m-d',strtotime('+'.$days.' day',strtotime($date)));
            $eDate=date('Y-m-d',strtotime('+'.($days+1).' day',strtotime($date)));
            $dRange=[':bDate'=>$bDate,':eDate'=>$eDate];
            $overTime=$days*24*3600;
            // foreach($eidRows as $eidRow){
                // $eid=$eidRow['EID'];
                RedisDM::delRfwUrlByResult($eid,date('Ymd',strtotime($dRange[':bDate'])));
                self::initRfwUrlByResultWithDateRange($eid,$dRange,$overTime);
            // }
            $days--;
        }
    }

    //在时间区间内统计各类恶意网址的数量
    private static function initRfwUrlByResultWithDateRange($eid,$dRange,$overTime)
    {
        $date=date('Ymd',strtotime($dRange[':bDate']));
        MC::clean();
        MC::$eid=$eid;
        $resultRows=MC::getAll(sprintf('SELECT result,count(1) count FROM RFW_UrlInterceptLog_%s where time>=:bDate and time<:eDate group by result',$eid),$dRange);
        // $resultRows=[
        //     ['result'=>1,'count'=>100],
        //     ['result'=>8,'count'=>110],
        //     ['result'=>13,'count'=>123],
        //     ['result'=>10,'count'=>145],
        // ];
        foreach($resultRows as $resultRow){
            RedisDM::addRfwUrlByResult($eid,$resultRow['result'],$date,$resultRow['count'],$overTime);
        }
    }

    //骚扰拦截
    public static function initPhoneSpam($eid)
    {
        // $eidRows=EnterpriseModel::getEids();
        //$eidRows=[['EID'=>'D05D6DE488005623']];
        $days=7;
        MC::clean();
        $date=date('Y-m-d',strtotime('-'.$days.' day'));
        while($days>0){
            $bDate=date('Y-m-d',strtotime('+'.$days.' day',strtotime($date)));
            $eDate=date('Y-m-d',strtotime('+'.($days+1).' day',strtotime($date)));
            $dRange=[':bDate'=>$bDate,':eDate'=>$eDate];
            $overTime=$days*24*3600;
            // foreach($eidRows as $eidRow){
                // $eid=$eidRow['EID'];
                self::initPhoneSpamByPhone($eid,$dRange,$overTime);
                self::initPhoneSpamByMsg($eid,$dRange,$overTime);
            // }
            $days--;
        }
    }
    private static function initPhoneSpamByPhone($eid,$dRange,$overTime)
    {
       // print_r($dRange);
        $date=date('Ymd',strtotime($dRange[':bDate']));
        RedisDM::delPhoneSpam($eid,'hp',$date);
        MC::clean();
        MC::$eid=$eid;
        $resultRows=MC::getAll(sprintf('SELECT count(1) count FROM AND_Phone_SpamPhone_%s where date>=:bDate and date<:eDate',$eid),$dRange);
        // $resultRows=[
        //     ['count'=>1]
        // ];
        foreach($resultRows as $resultRow){
            RedisDM::addPhoneSpam($eid,'hp',$date,$resultRow['count'],$overTime);
        }
    }
    private static function initPhoneSpamByMsg($eid,$dRange,$overTime)
    {
        $date=date('Ymd',strtotime($dRange[':bDate']));
        RedisDM::delPhoneSpam($eid,'hm',$date);
        MC::clean();
        MC::$eid=$eid;
        $resultRows=MC::getAll(sprintf('SELECT count(1) count FROM AND_Phone_SpamMsg_%s where date>=:bDate and date<:eDate',$eid),$dRange);
        // $resultRows=[
        //     ['count'=>12]
        // ];
        foreach($resultRows as $resultRow){
            RedisDM::addPhoneSpam($eid,'hm',$date,$resultRow['count'],$overTime);
        }
    }

    //初始化违规联网
    public static function initRfwBNS($eid)
    {
        // $eidRows=EnterpriseModel::getEids();
        //$eidRows=[['EID'=>'D05D6DE488005623']];
        $days=7;
        $date=date('Y-m-d',strtotime('-'.$days.' day'));
        MC::clean();
        while($days>0){
            $bDate=date('Y-m-d',strtotime('+'.$days.' day',strtotime($date)));
            $eDate=date('Y-m-d',strtotime('+'.($days+1).' day',strtotime($date)));
            $dRange=[':bDate'=>$bDate,':eDate'=>$eDate];
            $overTime=$days*24*3600;
            // foreach($eidRows as $eidRow){
                // $eid=$eidRow['EID'];
                self::initRfwB($eid,$dRange,$overTime);
                self::initRfwN($eid,$dRange,$overTime);
                self::initRfwS($eid,$dRange,$overTime);
            // }
            $days--;
        }
    }

    private function initRfwB($eid,$dRange,$overTime)
    {
        $date=date('Ymd',strtotime($dRange[':bDate']));
        MC::clean();
        MC::$eid=$eid;
        RedisDM::delRfwBNS($eid,'nb',$date);
        $resultRows=MC::getAll(sprintf('SELECT count(1) count FROM RFW_BrowsingAuditLog_%s where result=2 and time>=:bDate and time<:eDate',$eid),$dRange);
        // $resultRows=[
        //     ['count'=>10],
        // ];
        foreach($resultRows as $resultRow){
            RedisDM::addRfwBNS($eid,'nb',$date,$resultRow['count'],$overTime);
        }
    }
    private function initRfwN($eid,$dRange,$overTime)
    {
        $date=date('Ymd',strtotime($dRange[':bDate']));
        MC::clean();
        MC::$eid=$eid;
        RedisDM::delRfwBNS($eid,'nn',$date);
        $resultRows=MC::getAll(sprintf('SELECT count(1) count FROM RFW_NetProcAuditLog_%s where time>=:bDate and time<:eDate',$eid),$dRange);
        // $resultRows=[
        //     ['count'=>15],
        // ];
        foreach($resultRows as $resultRow){
            RedisDM::addRfwBNS($eid,'nn',$date,$resultRow['count'],$overTime);
        }
    }
    private function initRfwS($eid,$dRange,$overTime)
    {
        $date=date('Ymd',strtotime($dRange[':bDate']));
        MC::clean();
        MC::$eid=$eid;
        RedisDM::delRfwBNS($eid,'ns',$date);
        $resultRows=MC::getAll(sprintf('SELECT count(1) count FROM RFW_SharedResAccessAuditLog_%s where action=9 and time>=:bDate and time<:eDate',$eid),$dRange);
        // $resultRows=[
        //     ['count'=>20],
        // ];
        foreach($resultRows as $resultRow){
            RedisDM::addRfwBNS($eid,'ns',$date,$resultRow['count'],$overTime);
        }
    }

    //本日流量
    public static function initRfwTFA($eid)
    {
        // $eidRows=EnterpriseModel::getEids();
        //$eidRows=[['EID'=>'D05D6DE488005623']];
        $days=7;
        $date=date('Y-m-d',strtotime('-'.$days.' day'));
        MC::clean();
        // foreach($eidRows as $eidRow){
            // $eid=$eidRow['EID'];
            self::initRfwTFAByDate($eid);
        // }
    }

    private static function initRfwTFAByDate($eid)
    {
        $date=date('Ymd');
        RedisDM::delRfwTFA('sf:'.$eid.':'.$date);
        MC::clean();
        MC::$eid=$eid;
        $dRange=[':bDate'=>date('Y-m-d'),':eDate'=>date('Y-m-d',strtotime('+1 day'))];
        $resultRows=MC::getAll(sprintf('select sguid,sum(updateflow) up,sum(downloadflow) down,(sum(updateflow)+sum(downloadflow)) total from RFW_TerminalFlowAuditLog_%s  where time>=:bDate and time<:eDate group by sguid order by total',$eid),$dRange);
        $overTime=7*24*3600;
        foreach($resultRows as $result){
            //print_r($result);
            RedisDM::delRfwTFA('sf:'.$eid.':'.$date.'1');
            RedisDM::delRfwTFA('sf:'.$eid.':'.$date.'2');
            RedisDM::addRfwTFASguid($eid,$result['sguid'],$date,$result['total'],$overTime);
            RedisDM::addRfwTFABySguid($eid,$result['sguid'],1,$date,$result['up'],$overTime);
            RedisDM::addRfwTFABySguid($eid,$result['sguid'],2,$date,$result['down'],$overTime);
        }
    }
}