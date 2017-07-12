<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-10-17
 * Time: 13:42
 */

namespace Plugs\IOA\Library\Util;

/**
 * email工具类
 * Class EmailUtil
 * @package Common\Util
 */
class EmailUtil {
    /**
     * 验证是否邮箱格式
     * @param $email    邮箱
     * @return mixed
     */
    public static function isEmail($email){
        return !(filter_var($email, FILTER_VALIDATE_EMAIL)  === false);
    }
}