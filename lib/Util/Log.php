<?php
namespace Lib\Util;

use Lib\Store\MongoClient;
use Monolog\Handler\MongoDBHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{
    protected static $instance;
    protected static $fileInstance;

    private function __construct(){}

    protected static function _init(){
        if(self::$instance !== null){
            return;
        }
        $log = new Logger('rec');
        $mongodb = new MongoDBHandler(MongoClient::getInstance(), MONGO_MANAGE_DB, "program_log");
        $log->pushHandler($mongodb);
        self::$instance = $log;
    }

    public static function __callStatic($name, array $args)
    {
        self::_init();
        if($name === 'add'){
            $name = 'info';
        }
        return call_user_func_array([self::$instance, $name], $args);
    }

    public static function fileInstance($for="default")
    {
        if(self::$fileInstance){
            return self::$fileInstance;
        }
        $log = new Logger($for);
        $file = sprintf(__DIR__ . '/../../logs/%s/%d/%d/%d.log', $for, date('Y'), date('m'), date('d'));
        $log->pushHandler(new StreamHandler($file));
        self::$fileInstance = $log;
        return $log;
    }

}

