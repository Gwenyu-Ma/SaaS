<?php
/**
 * Created by PhpStorm.
 * User: Huangqiaoyun
 * Date: 15-6-25
 * Time: 下午4:10
 */

/**
 * 判断变量是否为空
 * @param $value   判断字符串
 * @return bool     返回是否为空
 */
function isEmpty($value){
    if(!isset($value)){
        return true;
    }

    if(empty($value)){
        return true;
    }

    if(is_null($value)){
        return true;
    }

    return false;
}
