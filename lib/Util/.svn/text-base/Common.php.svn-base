<?php


namespace Lib\Util;
use Lib\Util\Api;
use \Lib\Store\RdKFK;
class Common
{
    public static function os($os)
    {
        return preg_replace([
            '/Microsoft ?/i',
            '/Service\s*Pack\s/i',
            '/ ?\(build\s*\d+\),?/i',
            '/32-bit/i',
            '/64-bit/i',
        ], [
            '',
            'SP',
            '',
            '',
            'x64',
        ], $os);
    }

    public static function mac($mac)
    {
        return strtoupper(str_replace (':', '-', $mac));
    }

    private static function getURL()
    {
        $st = select_manage_collection('setting');
        $row = $st->findOne(['msg_url' => ['$exists' => true]]);
        if ($row) {
            return $row['msg_url'];
        }
        return false;
    }

    public static function makeMsg($types, $context, $title)
    {
        $api = new Api();
        $api->baseURL = self::getURL();
        $args['types'] = $types;
        $args['context'] = $context;
        $args['title'] = $title;
        // $args['file'] = $file;
        return $api->post('/api/makeMsg.php', $args);
    }

    /**
     * kafka 和 MySQL算法统一
     * @param $s
     * @return int
     */
    private static function getv($eid)
    {
//        $arr = str_split($eid, 1);
//        $v = 0;
//        for ($i = 0; $i < strlen($eid); $i++) {
//            $v = $v + ord($arr[$i]);
//        }

        $v = 0;
        if(strlen($eid)==16){
            $a = hexdec(substr($eid, 0, 4));
            $b = hexdec(substr($eid, 4, 4));
            $c = hexdec(substr($eid, 8, 4));
            $d = hexdec(substr($eid, 12, 4));
            $v = $a + $b + $c + $d ;
        }
        return $v;
    }

    /**
     * @param $id
     * @param $jdata 数组格式数据
     */
    public static function writeKafka($id,$data)
    {
//return;
        //写kafka
        $partCount = RdKFK::getPartitionsCount();
        $partitions = self::getv($id) % $partCount;
        //Log::add('API '.$data['logtype'].' Kafka', array('log' => json_encode($data, JSON_UNESCAPED_UNICODE)));
        RdKFK::getInstance()->produce($partitions, 0, json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    /**
     * @param $id
     * @param $jdata json 格式数据
     */
    public static function writeJsonToKafka($id,$jdata)
    {
        //写kafka
        $partCount = RdKFK::getPartitionsCount();
        $partitions = self::getv($id) % $partCount;
        RdKFK::getInstance()->produce($partitions, 0, $jdata);
    }

}
