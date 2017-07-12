<?php
require_once(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__.'/../models/IoaSguid.php');
use Lib\Store\RedisCluster as Redis;
use Lib\Util\Log;

define('REDIS_DISK_QUEUE', 'disk_queue');
for(;;){
    $data = Redis::rPop(REDIS_DISK_QUEUE);
    if($data === false){
        break;
    }
    $data = json_decode($data, true);
    try{
        $objIoa = new IoaSguidModel();

        $ok = $objIoa->setDiskAccess($data['eid'],$data['sguid'],$data['disk']);
        if(!$ok){
            Log::add(REDIS_DISK_QUEUE.'_ERROR', $data);
        }else{
            Log::add(REDIS_DISK_QUEUE.'_OK', $data);
        }
    }catch(\Exception $e){
        $data['Exception'] = $e;
        Log::add(REDIS_DISK_QUEUE.'_ERROR', $data);
    }
}