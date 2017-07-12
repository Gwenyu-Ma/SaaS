<?php

/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-11-29
 * Time: 15:15
 */

/**
 * ioaSdk运行环境变量
 * Class IoaRunVariable
 */
class IoaRunVariable {
    /*运行环境的参数*/

    /*调试状态*/
    private static $lib_debug_state = false;

    /*日志收集器开关*/
    private static $lib_logger_state= true;

    /*类库的根存放路径*/
    private static $lib_root;

    /*获取被调用的类库的类所在的命名空间*/
    private static $lib_call_namespace;

    /*获取被调用的类库的类名*/
    private static $lib_call_class;

    /*获取被调用的类库的类中方法的方法名*/
    private static $lib_call_method;

    /*获取被调用的类库的类中方法的输入参数*/
    private static $lib_call_args;

    /*接口数据输入输出格式*/
    private static $lib_data_type;

    /**
     * 初始化相关环境变量
     */
    public function init(){
        self::$lib_root = __DIR__;
    }

    /**
     * 获取类库的调试状态
     */
    public static function getLibDebugState(){
        return self::$lib_debug_state;
    }

    /**
     * 设置调试状态
     * @param $debugState   调试状态
     */
    public static function setLibDebugState($debugState){
        self::$lib_debug_state = $debugState;
    }

    /**
     * 获取类库的根路径
     * @return mixed
     */
    public static function getLibRoot(){
        return self::$lib_root;
    }

    /**
     * 获取被调用的类所在的命名空间
     * @return mixed
     */
    public static function getCallNamespace(){
        return self::$lib_call_namespace;
    }

    public static function setCallNamespace($namespace){
        self::$lib_call_namespace = $namespace;
    }

    /**
     * 获取调用类库的类接口的类名
     * @return mixed
     */
    public static function getCallClass(){
        return self::$lib_call_class;
    }

    /**
     * 设置被调用类库的类接口的类名
     * @param $class 类名
     */
    public static function setCallClass($class){
        self::$lib_call_class = $class;
    }

    /**
     * 获取调用类库的类接口中的方法的方法名
     * @return mixed
     */
    public static function getCallMethod(){
        return self::$lib_call_method;
    }

    /**
     * 设置调用类库的类接口中的方法的方法名
     * @param $method   方法
     */
    public static function setCallMethod($method){
        self::$lib_call_method = $method;
    }

    /**
     * 返回类库中被调用的类中方法的输入参数
     * @return mixed
     */
    public static function getCallArgs(){
        return self::$lib_call_args;
    }

    /**
     * 设置类库中被调用的类中方法的输入参数
     * @param $args
     */
    public static function setCallArgs($args){
        self::$lib_call_args = $args;
    }

    /**
     * 返回接口数据输入输出的类型
     * @return mixed
     */
    public static function getTransferDataType(){
        return self::$lib_data_type;
    }

    /**
     * 设置接口数据输入输出的类型
     * @param $dataType 数据类型
     */
    public static function setTransferDataType($dataType){
        self::$lib_data_type = $dataType;
    }

    /**
     * 获取类库的日志开关状态
     */
    public static function getLibLoggerState(){
        return self::$lib_logger_state;
    }

    /**
     * 设置日志开关状态
     * @param $loggerState   日志开关状态
     */
    public static function setLibLoggerState($loggerState){
        self::$lib_logger_state = $loggerState;
    }
}