<?php
/*
 * func:本脚本在crontab中每分钟执行，定时去IOA中获取当前eid和sguid需要的userid，并入库
 */
require_once(__DIR__ . '/../../vendor/autoload.php');

use Lib\Store\RedisCluster as Redis;
use Lib\Util\Log;
use Lib\Util\Ioa;

define('REDIS_SGUID_QUEUE', 'ioa_sguid_queue');
for(;;){
    $data = Redis::rPop(REDIS_SGUID_QUEUE);
    if($data === false){
        break;
    }
    $data = json_decode($data, true);
    try{
        Ioa::terminalAddMember($data['sguid'],$data['eid']);
    }catch(\Exception $e){
        Redis::lPush(REDIS_SGUID_QUEUE, json_encode(array('eid'=>$data['eid'],'sguid'=>$data['sguid'])));
        Log::add("ScriptGetIoaUseridError",array("msg" => 'Script Ioa Userid is NULL'));
    }
}