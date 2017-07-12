<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-11-25
 * Time: 13:51
 */

namespace Plugs\IOA\Api;
use Plugs\IOA\Conf\IErrCode;
use Plugs\IOA\Conf\IErrMsg;
use Plugs\IOA\Library\Entity\ResultEntity;
use Plugs\IOA\Library\Exception\ArgumentException;
use Plugs\IOA\Library\Exception\CommonException;
use Plugs\IOA\Library\Logger\Logger;


/**
 * 控制基类
 * Class Basic
 * @package Api
 */
class BasicApi {
    /**
     * 实现调用方法前的初始化操作
     * 如果使用类的实例调用$method，但$method方法不是公有的，就会触发此函数。
     * @param $method   类示例调用的方法名称
     * @param $args     方法参数
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $args) {
        /*若方法存在*/
        if (method_exists($this, $method)) {
            $beforeMethod = '__before_' + $method;

            /*定制花前置操作方法*/
            if (method_exists($this, $beforeMethod)) {
                /*定制的前置操作方法*/
                $result = call_user_func_array(array($this, $beforeMethod), array(
                    $method,
                    $args
                ));

                if($result === true){
                    /*执行被调用的方法*/
                    return call_user_func_array(array($this, $method), $args);
                }

                return $result;
            } else if (method_exists($this, "__before")) {
                /*通用的前置操作方法*/
                $result = call_user_func_array(array($this, "__before"), array(
                    $method,
                    $args
                ));

                if($result === true){
                    /*执行被调用的方法*/
                    return call_user_func_array(array($this, $method), $args);
                }

                return $result;
            } else{
                /*执行被调用的方法*/
                return call_user_func_array(array($this, $method), $args);
            }
        } else {
            throw new \Exception(IErrMsg::I_ERROR_METHOD_EXIST_NOT . $method, IErrCode::I_METHOD_EXIST_NOT);
        }
    }

    /**
     * 每个方法的前置操作
     * @param $method 调用方法名
     * @param $arrArgs 输入参数数组
     * @return ResultEntity|null
     */
    protected function __before($method, $arrArgs){
        $resultEntity = null;

        try{
            //echo "前置操作";
            /*获取运行环境信息*/
            $className  = get_called_class();
            $class      = new \ReflectionClass($className);
            $namespace  = $class->getNamespaceName();

            /*监控运行的环境变量*/
            \IoaRunVariable::setCallNamespace($namespace);
            \IoaRunVariable::setCallClass($className);
            \IoaRunVariable::setCallMethod($method);
            \IoaRunVariable::setCallArgs($arrArgs);

            /*对请求参数进行校验*/
            $this->verifyArgs();

        } catch (\Exception $e){
            $resultEntity = CommonException::getErrResult($e);
        } finally{
            if(!is_null($resultEntity)){
                $this->logWrite($arrArgs, $resultEntity);

                return $resultEntity;
            } else{
                return true;
            }
        }
    }

    /**
     * 每个方法的后置操作，这个无法实现自动调用
     * @param $output   输出数据
     * @return ResultEntity|null
     */
    protected function __after($output){
        $resultEntity = null;

        try{
            /*启动日志收集器*/
            $inputData = \IoaRunVariable::getCallArgs();
            $this->logWrite($inputData, $output,"接口输入输出");
        } catch (\Exception $e){
            $resultEntity = CommonException::getErrResult($e);
        } finally{
            if($resultEntity != null){
                /*为了避免前面的日志收集器自身错误,采用另外一个错误日志收集器*/
                $logger = new Logger();
                $logger->log($resultEntity);
            }
        }
    }


    /**
     *
     * 对请求参数进行正确性验证
     * @throws ArgumentException
     */
    protected function verifyArgs(){
        $arrArgName = array();

        $fnName = \IoaRunVariable::getCallMethod();
        $fnArgs = \IoaRunVariable::getCallArgs();

        $reflectionFunction = new \ReflectionMethod($this, $fnName);
        $parameters = $reflectionFunction->getParameters();

        foreach ($parameters as $parameter){
            $name           = $parameter->getName();
            $position       = $parameter->getPosition();
            $defaultValue   = $parameter->getDefaultValue();
            $type           = gettype($defaultValue);

            array_push($arrArgName, array(
                "name"          => $name,
                "position"      => $position,
                "defaultValue"  => $defaultValue,
                "type"          => $type,
            ));
        }

        /*判断传入参数个数与要求参数个数不一致*/
        if(count($arrArgName) != count($fnArgs)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_COUNT_WRONG, IErrCode::I_ARGS_COUNT_WRONG);
        }

        /*判断请求参数的类型是否正确*/
        for($i = 0, $count = count($fnArgs); $i < $count; $i++){
            $argValue = $fnArgs[$i];

            foreach ($arrArgName as $argNameInfo){
                $name       = $argNameInfo["name"];
                $position   = $argNameInfo["position"];
                $type       = $argNameInfo["type"];

                if($position == $i){
                    if(gettype($argValue) != $type){
                        throw new ArgumentException(false, $name.IErrMsg::I_ERROR_ARG_TYPE_WRONG.$type, IErrCode::I_ARGS_TYPE_WRONG);
                    }

                    break;
                }
            }
        }
    }

    /**
     * 最终格式的数据输出
     * @param null $resultEntity    返回数据实体
     * @return null|string
     */
    protected function dataOutput($resultEntity = null){
        if(!isEmpty($resultEntity)){
            if($resultEntity instanceof ResultEntity){
                if(\IoaRunVariable::getTransferDataType() == "entity"){
                    return $resultEntity;
                }

                if(\IoaRunVariable::getTransferDataType() == "json"){
                    return $resultEntity->getJson();
                }
            }
        }

        return $resultEntity;
    }

    /**
     * 记录请求和响应数据
     * @param null $requestLog  请求参数
     * @param null $responseLog 响应参数
     * @param string $source 日志来源
     */
    protected function logWrite($requestLog = null, $responseLog = null, $source = ""){
        $logger = new Logger();
        $logger->apiLogFile($requestLog, $responseLog, $source);
    }
}