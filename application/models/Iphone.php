<?php
use \Lib\Store\RedisCluster as rds;
use \Lib\Util\Common as UCommon;
class IphoneModel
{
    public function uninstall( $eid,$sguid ){
        rds::lPush(REDIS_EPUNSET_QUEUE, json_encode(array(
            'eid' => $eid,
            'sguid' => $sguid,
            'unset' => 1
        )));

        UCommon::writeKafka($eid, [
            'logtype' => 'epinfo',
            'eid' => $eid,
            'sguid' => $sguid,
            'unset' => 1,
            'optype' => 'd'
        ]);

        $epinfo = rds::hGet(CACHE_REDIS_EP_PRE . $eid . $sguid, 'ep_info');
        $epinfo = json_decode($epinfo);

        $computerName = !empty($epinfo->computername) ? $epinfo->computername:"";

        $msg = sprintf('b:%s:pf:ep:uninst',$eid);
        $title = '终端'.$computerName.'已卸载';
        $body = <<<BODY
        终端被卸载 <br />
        终端名称：%s <br />
        卸载时间：%s <br />
BODY;
        $body = sprintf($body,
            $computerName,
            date("Y-m-d H:i:s")
        );
        $ok = \Lib\Util\Common::makeMsg(json_encode([$msg]), $body, $title);
        if(is_string($ok)){
            Log::add('ERROR', ['response'=>$ok]);
        }

        $rualog = '{ "logtype":"rua_log", "dwith":"0", "eid":"%s", "sguid":"%s", "data":[ { "time":"%s", "eventtype":0, "eventlevel":0, "eventsource":"", "category":0, "username":"", "description":"unset", "flowid":%s, "source":1, "action":3, "role":1, "oldver":"", "newver":"", "needreboot":0, "afterreboot":0, "info":"" } ] }';
        $rualog = sprintf($rualog,$eid,$sguid,date("Y-m-d H:i:s"),time());

        UCommon::writeJsonToKafka($eid, $rualog);
    }

}