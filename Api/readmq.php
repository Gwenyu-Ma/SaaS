<?php

require_once __DIR__ . '/../vendor/autoload.php';

include("Common/constant.php");
include("Common/rsfunc.php");
use \Lib\Store\RedisCluster as rds;
use Lib\Util\Log;

set_time_limit(0);  //不超时
$content = array();

while (true) {

    try {
        $content = rds::rPop(REDIS_EPINFO_QUEUE);

        if (empty($content)) {
            echo "队列无数据\n";
            break;
        }
        echo '插入计算机信息\n';
        $content = json_decode($content, true);
        //Log::add("before save Epinfo",$content);

        saveEpinfo($content);
    } catch (\Exception $e) {
        Log::add("Auto Api Error",array('ErrorMessage'=>$e->getMessage()));
    }
}

function saveEpinfo($content)
{
    if (json_last_error() !== JSON_ERROR_NONE) {
        return 'json_last_error';
    }
    $eid = $content['eid'];
    $sguid = $content['sguid'];
    $guid = $content['guid'];
    $systype = $content['systype'];
    $rip = $content['rip'];
    $edate = $content['edate'];

    $params = array(
        'eid' => $eid,
        'sguid' => $sguid,
        'guid' => $guid,
        'systype' => $systype,
        'rip' => $rip,
        'edate' => $edate
    );


    //存储上报日志
    $contentrows = $content['msg'];
    if (!empty($contentrows)) {
        $cnt = count($contentrows);
        foreach ($contentrows as $key => $value) {
            switch (intval($value['cmsgtype'])) {
                case 2001:
                    computerInfo($params, $value['content']);
                    break;
                case 2002:
                    //reportLog($params, $value['content']['reportcontent']);
                    break;
                default:
                    # code...
                    break;
            }
        }
    }

}
