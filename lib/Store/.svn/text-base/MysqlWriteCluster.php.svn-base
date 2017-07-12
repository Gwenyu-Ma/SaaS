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
//http://www.redbeanphp.com/index.php?p=/database ??????
use Tx\DB;
use ChromePhp as Console;

class MysqlWriteCluster extends DB
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
        $conf = $dbs[$i];
        $conf['read'] = $conf['write'];
        return $conf;
    }
}

