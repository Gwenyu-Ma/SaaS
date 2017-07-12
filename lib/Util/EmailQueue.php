<?php
//
// EmailQueue::push(array(array('name'=>'Tom', 'email'=>'tom@gmail.com')), 'Subject', 'Content');
//
namespace Lib\Util;

use Lib\Store\RedisCluster as Redis;

class EmailQueue
{
    // return number | false
    static public function push($tos, $subject, $body, $attachments=[])
    {
        $data = array(
            'tos' => $tos,
            'subject' => $subject,
            'body' => $body,
            'attachments' => $attachments,
        );
        return Redis::lPush(REDIS_EMAIL_QUEUE_KEY, json_encode($data));
    }
}
