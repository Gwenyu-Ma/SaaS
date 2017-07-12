<?php

use Lib\Util\Api;
use Lib\Util\Log;

class DownloadModel
{
    public $api;
    public $st;
    public function __construct()
    {
        $this->st = select_manage_collection('setting');
        $this->api = new Api();
        $this->api->baseURL = $this->getURL();
        if(!$this->api->baseURL){
            throw new \Exception('未配置download平台地址');
        }
    }

    public function setURL($url)
    {
    }

    public function getURL()
    {
        $urls = require(__DIR__ . '/../../config/urls.php');
        return $urls['dl_lan'];
    }

    public function addEID($eid)
    {
        $r = $this->api->get('/api/eid.php', ['eid' => $eid, 'op'=>'add']);
        if(!is_array($r) || $r['error'] !== null){
            Log::add("add_eid_error", ['error'=>$r]);
        }
    }

    // eid can be ''
    public function getPackage($eid, $platform)
    {
        $args['platform'] = $platform;
        $args['eid'] = $eid;
        return $this->api->get('/api/download.php', $args);
    }

    public function getLanPackage($eid, $platform)
    {
        $args['platform'] = $platform;
        $args['eid'] = $eid;
        return $this->api->getLan('/api/download.php', $args);
    }

}

