<?php
namespace DL\Model;

use Lib\Store\RedisCluster as Redis;

class Base 
{
    public function get($platform)
    {
        return Redis::get(DL_PFX . $platform);
    }

    public function set($platform, $package)
    {
        Redis::set(DL_PFX . $platform, $package);
        Redis::set(DL_PFX . $platform . "_md5", md5_file(__DIR__ . "/../file/$platform/base/".$package));
    }


    // 获取基础包名及md5
    public function getBase($platform)
    {
        $name = Redis::get(DL_PFX . $platform);
        $md5 = Redis::get(DL_PFX . $platform . "_md5");
        if(!$name){
            return null;
        }
        return array(
            'name' => $name,
            'md5' => $md5,
        );
    }

    public function getAll()
    {
        $bases[] = array_merge(['platform'=>'android'], (array)$this->getBase('android'));
        $bases[] = array_merge(['platform'=>'linux'], (array)$this->getBase('linux'));
        $bases[] = array_merge(['platform'=>'windows'], (array)$this->getBase('windows'));
        return $bases;
    }

}

