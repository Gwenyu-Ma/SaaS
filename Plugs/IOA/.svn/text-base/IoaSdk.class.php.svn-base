<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-11-28
 * Time: 16:30
 */

/**
 * 实现IOA的sdk的管理
 * Class IoaSdk
 */
class IoaSdk {
    /**
     * 进行sdk初始化操作
     */
    public function init(){
        /*加载配置*/
        include_once dirname(__FILE__)."/Conf/config.php";

        /*自动加载sdk类库*/
        include_once dirname(__FILE__)."/AutoLoadSdk.class.php";
        $autoLoadSdk = AutoLoadSdk::getInstance();
        $autoLoadSdk->autoLoadSdk();

        /*初始化类库运行信息*/
        include_once dirname(__FILE__)."/IoaRunVariable.class.php";
        $ioaRunVariable = new IoaRunVariable();
        $ioaRunVariable->init();
    }

    /**
     * 设置调试状态，若debug状态开启，自动开启日志开关
     * @param boolean $debugState   调试状态
     */
    public function setDebugState($debugState = false){
        IoaRunVariable::setLibDebugState($debugState);

        if($debugState){
            IoaRunVariable::setLibLoggerState($debugState);
        }
    }

    /**
     * 设置日志收集器开关,若debug状态开启，不允许关闭日志开关
     * @param boolean $loggerState  日志收集器开启状态
     */
    public function setLoggerState($loggerState = true){
        if(IoaRunVariable::getLibDebugState()){
            IoaRunVariable::setLibLoggerState(IoaRunVariable::getLibDebugState());
        } else{
            IoaRunVariable::setLibLoggerState($loggerState);
        }
    }

    /**
     * 接口数据输入输出格式
     * @param string $dataType 数据格式
     */
    public function setTransferDataType($dataType = "json"){
        IoaRunVariable::setTransferDataType($dataType);
    }
}