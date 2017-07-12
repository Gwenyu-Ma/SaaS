<?php

require_once __DIR__ . '/../vendor/autoload.php';

include("Common/rsfunc.php");
use \Lib\Store\RedisCluster as rds;
use Lib\Util\Log;

//background worker
set_time_limit(0);  //不超时
$content = array();

while (true) {
    $content = rds::rPop(REDIS_EPUNSET_QUEUE);
    if (empty($content)) {
        echo "卸载队列无数据\n";
        break;
    }

    try {
        echo '更新卸载信息\n';
        $content = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::add("before Update unset json_last_error",$content);
            return 'json_last_error';
        }
        //Log::add("before Update unset",$content);
        updateUnset($content);

    } catch (\Exception $e) {
        Log::add("Auto Update unset Error",array('ErrorMessage'=>$e->getMessage()));
    }
}

function updateUnset($content)
{
    $eid = $content['eid'];
    $sguid = $content['sguid'];
    $unset = $content['unset'];

    $params = array(
        'eid' => $eid,
        'sguid' => $sguid,
        'unset' => $unset
    );

    updateEpUnset($params);
}
