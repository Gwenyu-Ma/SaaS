<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-11-02
 * Time: 17:38
 */

namespace Plugs\IOA\Library\Util;

/**
 * 日期类
 * Class DateUtil
 * @package Common\Util
 */
class DateUtil {
    /**
     * 获取当前时间戳
     * @return int
     */
    public static function getCurrentTimestamp (){
        return time();
    }

    /**
     * 获取当前日期和时间
     * @param string $format
     * @return false|string 返回日期和时间
     */
    public static function getCurrentDateTime($format = 'Y-m-d H:i:s'){
        date_default_timezone_set('Etc/GMT-8');
        return date($format, time());
    }

    /**
     * 获取utc标准时间
     * @param string $format
     * @return false|string
     */
    public static function getCurrentUTCDateTime($format = 'Y-m-d H:i:s'){
        date_default_timezone_set('UTC');
        return date($format, time());
    }

    /**
     * 获取当前日期
     * @return false|string
     */
    public static function getCurrentDate(){
        return self::getCurrentDateTime("Y-m-d");
    }

    /**
     * 返回时间戳
     * @param $date     日期字符串
     * @return false|int
     */
    public static function getTimestamp($date){
        if(!isEmpty($date)){
            return strtotime($date);
        }

        return time();
    }

    /**
     * 返回当前的年份
     * @param $date     日期
     * @return false|string
     */
    public static function getYear($date){
        $date = self::getTimestamp($date);

        return date("Y",$date);
    }

    /**
     * 返回日期中的月份
     * @param $date string    日期
     * @return false|string
     */
    public static function getMonth($date = ""){
        $date = self::getTimestamp($date);

        return date("m",$date);
    }

    /**
     * 返回日期中的日期
     * @param $date string    日期
     * @return false|string
     */
    public static function getDay($date = ""){
        $date = self::getTimestamp($date);

        return date("d", $date);
    }

    /**
     * 产品剩余月份
     * @param $expire_date
     * @param string $effect_date
     * @return float|int
     */
    public static function leftMonths($expire_date, $effect_date=''){
        $months = 0;
        $time = ( $effect_date ) ? $effect_date:time();
        $d1 = date('Y-m-d',strtotime(date("Y-m-d",$time)." +1 day"));
        $d2 = date('Y-m-d',$expire_date);
        $days = ( strtotime($d2) - strtotime($d1) )/86400 + 2;

        if($days<=7) {
            $months = 0;
        }else if($days>7 && $days<=30){
            $months =1;
        }else if ( $days> 30 ){
            $months = ( $days%30< 15)? intval($days/30) : ceil($days/30);
        }
        return $months;
    }

    /**
     * 获取当前时间的毫秒级
     * @return float
     */
    public static function msectime() {
        list($tmp1, $tmp2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($tmp1) + floatval($tmp2)) * 1000);
    }

    /**
     * 将时间戳转换成毫秒级别
     * @param int $time
     * @return int
     */
    public static function microtimeFloat($time=0){
        return $time*1000;
    }

    /**
     * 获取微妙数
     * @return float
     */
    public static function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }
}