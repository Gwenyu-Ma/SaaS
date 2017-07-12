<?php
//
// list methods: https://github.com/phpredis/phpredis
//
namespace Lib\Store;

use \RedisCluster as RC;

class RedisCluster
{
    protected static $instance;

    private function __construct(){}

    protected static function _init()
    {
        if(self::$instance !== null){
            return;
        }
        $config = require(__DIR__.'/../../config/rediscluster.php');
        self::$instance = new RC(NULL, $config);
        self::$instance->setOption(RC::OPT_SLAVE_FAILOVER, RC::FAILOVER_ERROR);
    }

    public static function __callStatic($name, array $args)
    {
        self::_init();
        return call_user_func_array([self::$instance, $name], $args);
    }

    public static function getInstance()
    {
        self::_init();
        return self::$instance;
    }

}

