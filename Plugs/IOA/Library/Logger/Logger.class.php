<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-10-25
 * Time: 14:41
 */

namespace Plugs\IOA\Library\Logger;

use Plugs\IOA\Library\Entity\ResultEntity;
use Plugs\IOA\Library\Exception\CommonException;
use Plugs\IOA\Library\Util\DateUtil;
use Plugs\IOA\Library\Util\FileUtil;


/**
 * 日志纪录类,当启用debug状态时记录日志
 * Class LogLogic
 * @package Log\Logic
 */
class Logger {
    private $separator  = "\t";
    private $isLogging  = true;

    /**
     * 日志类控制器
     * Logger constructor.
     */
    public function __construct(){
        $this->isLogging = \IoaRunVariable::getLibLoggerState();
    }

    /**
     * 实现接口访问的日志记录到文件中
     * @param $request              请求数据
     * @param $source   string      来源标签
     * @param $response             返回数据
     */
    public function apiLogFile($request, $response, $source=""){
        if($this->isLogging){
            $dateTime   = DateUtil::getCurrentDateTime();

            /*格式组成,模块_控制器_方法_日期*/
            $fileName       = $this->getFullFileName();
            $interfaceFile  = new FileUtil($fileName);

            if($request instanceof ResultEntity){
                $requestData = $request->getJson();
            } else{
                $requestData = $request;
            }

            if($response instanceof ResultEntity){
                $responseData = $response->getJson(true);
            } else{
                $responseData = $response;
            }

            $requestData    = is_array($requestData)  ? json_encode($requestData, JSON_UNESCAPED_UNICODE) : $requestData;
            $responseData   = is_array($responseData) ? json_encode($responseData, JSON_UNESCAPED_UNICODE) : $responseData;

            $requestData    = isEmpty($requestData) ? "请求数据为空" : $requestData;
            $responseData   = isEmpty($responseData) ? "响应数据为空" : $responseData;
            $requestData    = $source."请求数据：".$requestData;
            $responseData   = $source."响应数据：".$responseData;
            $dateTime       = $dateTime.'----------------------------------------------';

            $interfaceFile->write($dateTime);
            $interfaceFile->write($requestData);
            $interfaceFile->write($responseData);
            $interfaceFile->write($dateTime);
        }
    }

    /**
     * 记录系统操作抛出的异常信息
     * @param \Exception $e 异常类
     * debug_backtrace()方法可以获得当前调用的方法
     * get_class($this)可以获得当前的调用类
     */
    public function exceptionLog(\Exception $e){
        if($this->isLogging) {
            $result = CommonException::getErrResult($e);

            if($result){
                $exceptionData  = $result->getJson(true);
                $dateTime       = DateUtil::getCurrentDateTime();

                /*格式组成,模块_控制器_方法_日期*/
                $fileName       = $this->getFullFileName();
                $exceptionFile  = new FileUtil($fileName);

                $exceptionData  = json_encode($exceptionData, JSON_UNESCAPED_UNICODE);
                //$backTraceData  = json_encode(debug_backtrace(), JSON_UNESCAPED_UNICODE);

                $exceptionData  = "操作异常数据：".$exceptionData;
                //$backTraceData  = "异常追踪：".$backTraceData;

                $dateTime       = $dateTime.'系统访问操作抛出异常----------------------------------------------';

                $exceptionFile->write($dateTime);
                $exceptionFile->write($exceptionData);
                //$exceptionFile->write($backTraceData);
                $exceptionFile->write($dateTime);
            }
        }
    }

    /**
     * 通用日志记录器
     * @param $logData
     * @return bool
     */
    public function log($logData){
        if($this->isLogging){
            if ("" == $logData || array() == $logData) {
                return false;
            }

            if (is_array($logData)) {
                $logData = implode($this->separator, $logData);
            }

            if($logData instanceof ResultEntity){
                $logData = $logData->getJson(true);
            }

            $logData = $logData. "\n";

            fwrite($this->getFileHandle(), $logData);
        }
    }

    /**
     * 创建并获取日志文件句柄
     * @return resource
     */
    private function getFileHandle(){
        $filePath   = $this->getFullFileName();
        $logDir     = dirname($filePath);

        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $fileHandle = fopen($filePath, "a");

        return $fileHandle;
    }

    /**
     * 获取含有完整路径的文件名
     * @return string 返回文件路径详情
     */
    private function getFullFileName(){
        $root       = \IoaRunVariable::getLibRoot();
        $class      = \IoaRunVariable::getCallClass();
        $method     = \IoaRunVariable::getCallMethod();
        $date       = DateUtil::getCurrentDate();

        $filePath   = $root ."/Logs/". $class . '/'. $method .'_'.$date.'.log';
        $filePath   = str_replace("::", "/", $filePath);
        $filePath   = str_replace("\\", "/", $filePath);

        return $filePath;
    }
}