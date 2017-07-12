<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-11-02
 * Time: 17:52
 */

namespace Plugs\IOA\Library\Util;

/**
 * 手机相关验证工具类
 * Class MobileUtil
 * @package Common\Util
 */
class MobileUtil {
    /**
     * 校验字符串是否是电话/手机号
     * @param $str
     * @return bool
     */
    public static function checkPhone($str){
        $isMob="/^1(5[0-35-9]|8[06789]|3[0-9]|47|45|7[6-8])\d{8}$/";
        $isTel="/^(0(10|21|22|23|[1-9][0-9]{2})(-|))?[0-9]{7,8}$/";
        if( preg_match($isMob,$str) || preg_match($isTel,$str))    {
            return true;
        }

        return false;
    }
}