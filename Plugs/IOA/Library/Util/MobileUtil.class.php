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
        $isMob="/^(13|14|15|17|18)\\d{9}$/";
        if(preg_match($isMob,$str))    {
            return true;
        }

        return false;
    }
}