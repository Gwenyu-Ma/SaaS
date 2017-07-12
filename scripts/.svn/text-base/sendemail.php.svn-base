<?php
require_once(__DIR__ . '/../vendor/autoload.php');

use Lib\Store\RedisCluster as Redis;
use Lib\Util\Email;
use Lib\Util\Log;

for(;;){
    $data = Redis::rPop(REDIS_EMAIL_QUEUE_KEY);
    if($data === false){
        break;
    }
    $data = json_decode($data, true);
    try{
        $ok = Email::send($data['tos'], $data['subject'], $data['body'], $data['attachments']);
        if($ok){
            Log::add("EMAIL_OK", $data);
        }else{
            Log::add("EMAIL_ERROR", $data);
        }
    }catch(\Exception $e){
        $data['Exception'] = $e;
        Log::add("EMAIL_ERROR", $data);
    }
}

