<?php

use Lib\Util\Api;
use Lib\Util\Log;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-12-10
 * Time: 14:34
 */
class DownloaderModel
{
    public $api;
    public function __construct()
    {
        $this->api = new Api();
        $this->api->baseURL = $this->getURL();
        if(!$this->api->baseURL){
            throw new \Exception('未配置download平台地址');
        }
    }

    private function getURL()
    {
        $urls = require(__DIR__ . '/../../config/urls.php');
        return $urls['dl'];
    }

    public function addEID($eid)
    {
        $r = $this->api->get('/api/eid.php', ['eid' => $eid, 'op'=>'add']);
        if(!is_array($r) || $r['error'] !== null){
            Log::add("add_eid_error", ['error'=>$r]);
        }
    }


    public function addSubscriber($subscriber, $types)
    {
        $args['subscriber'] = $subscriber;
        $args['types'] = $types;
        return $this->api->post('/api/addSubscriber.php', $args);
    }

}