<?php
namespace DL\Model;

use Lib\Store\RedisCluster as Redis;
use Lib\Util\Log;

class Sign
{
    public function android($eid, $base, $dist)
    {
        if(is_file($dist))unlink($dist);
        if(!copy($base, $dist)){
            return 'copy error';
        }
        $dir = dirname($dist);
        $name = basename($dist);

        if(!is_dir($dir . '/META-INF')){
            mkdir($dir . '/META-INF');
        }

        $eidfile = '@'.base64_encode("eid@$eid");
        $eidpath = $dir . '/META-INF/' . $eidfile;
        $fd = fopen($eidpath, 'w+');
        fclose($fd);
        exec("cd $dir && zip -g $name META-INF/$eidfile", $outputs, $result);
        if($result !== 0){
            return 'zip eid error';
        }

        $urls = require(__DIR__ . '/../../config/urls.php');
        $urlfile = '@'.base64_encode("url@".$urls['platform']);
        $urlpath = $dir . '/META-INF/' . $urlfile;
        $fd = fopen($urlpath, 'w+');
        fclose($fd);
        exec("cd $dir && zip -g $name META-INF/$urlfile", $outputs, $result);
        if($result !== 0){
            return 'zip url error';
        }
        return true;
    }

    public function linux($eid, $base, $dist)
    {
        if(is_file($dist))unlink($dist);
        if(!copy($base, $dist)){
            return 'copy error';
        }
        return true;
    }

    public function windows($eid, $base, $dist)
    {
        if(is_file($dist))unlink($dist);
        if(!copy($base, $dist)){
            return 'copy error';
        }
        $fd = fopen($dist, 'ab');
        $urls = require(__DIR__ . '/../../config/urls.php');
        $json = base64_encode(json_encode(array(
            'eid' => $eid,
            "dl_url" => $urls['exe'],
        )));
        fwrite($fd, pack("a4", "ring"));
        fwrite($fd, pack("l", strlen($json)));
        fwrite($fd, pack("a".strlen($json), $json));
        fclose($fd);

        if(get_cfg_var('env') !== 'production'){
            // old
            $cert = __DIR__ . '/../ca.pfx';
            exec("/usr/local/bin/osslsigncode sign -pkcs12 $cert -pass Rising2016Rx -t http://timestamp.globalsign.com/scripts/timestamp.dll -in $dist -out $dist.processing", $outputs, $result);
            if($result !== 0){
                return 'osslsigncode error';
            }
        }else{
            // new
            $body = \Unirest\Request\Body::multipart([], ['name'=>$dist]);
            $r = \Unirest\Request::post($urls['sign'], [], $body);
            if($r->code !== 200){
                return 'sign server response '.$r->code;
            }
            file_put_contents("$dist.processing", file_get_content($r->raw_body));
        }

        if(!rename("$dist.processing", $dist)){
            return 'rename error';
        }

        return true;
    }

    // 签所有的eid
    public function makeAll($platform)
    {
        $eids = Redis::zRevRange(DL_RDS_EIDS, 0, -1);
        if(!$eids){
            return;
        }
        foreach($eids as $eid){
            $this->make($platform, $eid);
        }
        return;
    }

    public function make($platform, $eid)
    {
        $base = Redis::get(DL_PFX . $platform);
        if(!$base){
            return;
        }
        $basePath = Path::base($platform, $base);
        $dist = $eid.'-'.$base;
        $distPath = Path::dist($platform, $eid, $dist);
        $old = Redis::hGet(DL_PFX . $eid, "${platform}_name");
        if($old === $dist){
            return;
        }

        $ok = $this->$platform($eid, $basePath, $distPath);
        if($ok !== true){
            echo "$platform:$eid:$basePath:$distPath:$ok\n";
            return;
        }
        Redis::hSet(DL_PFX . $eid, 'updated_at', time());
        Redis::hSet(DL_PFX . $eid, "${platform}_name", $dist);
        Redis::hSet(DL_PFX . $eid, "${platform}_md5", md5_file($distPath));
        if($old){
            $oldPath = Path::dist($platform, $eid, $old);
            if(is_file($oldPath))unlink($oldPath);
        }
        return;
    }

}

function file_get_content($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
};
