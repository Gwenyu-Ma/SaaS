<?php
namespace Lib\Store;

// Example:
//
// use \Lib\Store\Mysql;
// Mysql::getAll('select SQL');
// Mysql::getRow('select SQL');
// Mysql::exec('insert sql OR update SQL');
//
// more help: https://github.com/txthinking/DB/blob/master/tests/DBTest.php

use Tx\DB;

class MysqlCluster extends DB
{
    public static $eid;

    public static function setEID($eid)
    {
        self::$eid = $eid;
        self::clean();
    }

    public static function conf()
    {
        if(!self::$eid){
            throw new \Exception('NEED EID');
        }
        $a = hexdec(substr(self::$eid, 0, 4));
        $b = hexdec(substr(self::$eid, 4, 4));
        $c = hexdec(substr(self::$eid, 8, 4));
        $d = hexdec(substr(self::$eid, 12, 4));
        $dbs = require(__DIR__ . '/../../config/mysqlcluster.php');
        $i = ($a + $b + $c + $d)%count($dbs);
        return $dbs[$i];
    }
}

