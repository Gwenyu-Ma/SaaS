<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-12-01
 * Time: 11:37
 */

namespace Plugs\IOA\Library\Util;

/**
 * 序列化相关类
 * Class SequenceUtil
 * @package IOA\Util
 */
class SequenceUtil {
    /**
     * php获取uuid
     * @return string 返回uuid
     */
    public static function  getGuid(){
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
}