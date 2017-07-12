<?php
namespace Lib\Model;

use Lib\Store\RedisCluster as Redis;

class Test
{
    public static function openFinishFlag()
    {
        Redis::set("initComplete", 0);
    }

    public static function testInit()
    {
        return Redis::getInstance()->keys('*');
    }

    public static function testTimeout()
    {
        Redis::set('hello', '42');
        Redis::set('hello1','43');
        var_dump(Redis::setTimeout('hello', 3));
        //var_dump(Redis::expire('hello',3));
        Redis::expireAt('hello1',time()+3);
        sleep(5);
        var_dump(Redis::get('hello'));
        var_dump(Redis::get('hello1'));
    }

    public static function testHep($sguid)
    {
        $key = empty($sguid) ? '*' : $sguid;
        $keys = Redis::keys('hep_' . $key);
        //Redis::del($keys);
        //var_dump($keys);

        echo '<br />';
        foreach ($keys as $value) {
            echo Redis::hGet($value, 'g_info');
            echo '<br />';
            echo '<br />';
            echo Redis::hGet($value, 'p_info');
            echo '<br />';
            echo '<br />';
            echo Redis::hGet($value, 'p_ver');
            echo '<br />';
            echo '<br />';
            echo Redis::hGet($value, 'c_ver');
            echo '<br />';
            echo '<br />';
            echo Redis::hGet($value, 'ep_info');
            echo '<br />';
            echo Redis::hGet($value,'inblackmenu');
            echo '<br />';
        }
    }

    public static function testHeid($eid, $groupid)
    {
        $key = empty($sguid) ? '*' : $eid;
        $keys = Redis::getInstance()->keys('heid_' . $key);
        //Redis::del($keys);

        //var_dump($keys);

        foreach ($keys as $value) {
            echo Redis::hGet($value, 'p_global');
            if (!empty($groupid)) {
                echo '<br /><br />group policy:<br />';
                echo Redis::hGet($value, 'p_group_' . $groupid);
            }
            echo '<br />';
        }
    }

    public static function testLcmd($sguid)
    {
        $key = empty($sguid) ? '*' : $sguid;
        $keys = Redis::keys('lcmd_' . $key);

        foreach ($keys as $value) {
            $len = Redis::lLen($value);
            for ($i = 0; $i < $len; $i++) {
                echo $i . ' ' . Redis::lGet($value, $i) . ' : ';

                echo Redis::get(Redis::lGet($value, $i));
                echo '<br />';
            }
        }
    }

    public static function testStrcmd()
    {
        $keys = Redis::getInstance()->keys('strcmd*');
        var_dump($keys);
        echo '<br />';
        foreach ($keys as $value) {
            echo $value . ':';
            echo Redis::get($value);
            echo '<br />';
        }
    }

    public static function testOneClient($eid, $groupid, $sguid)
    {
        $keys = Redis::keys('hep_' . $sguid);

        var_dump($keys);
        echo '<br />';
        foreach ($keys as $value) {
            echo 'g_info' . '    ' . Redis::hGet($value, 'g_info');
            echo '<br />';
            echo 'p_info' . '    ' . Redis::hGet($value, 'p_info');
            echo '<br />';
            echo 'p_ver' . '    ' . Redis::hGet($value, 'p_ver');
            echo '<br />';
            echo 'c_ver' . '    ' . Redis::hGet($value, 'c_ver');
            echo '<br />';
            echo 'ep_info' . '    ' . Redis::hGet($value, 'ep_info');
            echo '<br />';
        }
        echo '---------------' . 'hep_' . $sguid . '----------------';

        $keys = Redis::getInstance()->keys('heid_' . $eid);
        //Redis::del($keys);

        var_dump($keys);

        foreach ($keys as $value) {
            echo Redis::hGet($value, 'p_global');
            echo '<br />00000';
            echo Redis::hGet($value, 'p_group_' . $groupid);
            echo '<br />00000';
        }

        echo '---------------' . 'heid_' . $eid . '----------------';

        $keys = Redis::getInstance()->keys('lcmd_' . $sguid);
        var_dump($keys);

        foreach ($keys as $value) {
            $len = Redis::lLen($value);
            for ($i = 0; $i < $len; $i++) {
                $cmd = Redis::lGet($value, $i);
                echo $i . '    ' . $cmd;

                echo '<br />';
                echo Redis::get($cmd);

                echo '<br />';
            }
        }

        echo '<br />' . '---------------' . 'lcmd_' . $sguid . '----------------';
    }

    public static function isBlackGroup($eid, $groupId)
    {
        var_dump(RedisDataManager::isBlackGroup($eid, $groupId));
        var_dump(RedisDataManager::isBlackGroup($eid, $groupId));
    }

}
