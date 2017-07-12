<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-07-14
 * Time: 13:37
 */

namespace Plugs\IOA\Library\Exception;
use Plugs\IOA\Library\Entity\ResultEntity;
use Plugs\IOA\Library\Logger\Logger;

/**
 * 自定义异常基类
 * Class CommonException
 * @package Direct\Exception
 */
class CommonException extends \Exception{
    /*错误操作状态*/
    protected $state;

    /*错误提示消息*/
    protected $message;

    /*错误code*/
    protected $code;

    /*返回数据*/
    protected $data;

    /*文件名*/
    protected $file;

    /*行数*/
    protected $line;


    /**
     * 构造器
     * CommonException constructor.
     * @param string $state     操作状态
     * @param String $message      操作消息
     * @param int $code      错误码
     * @param \Exception $data  返回数据
     */
    public function __construct($state, $message ="", $code = 0, $data = null){
        $this->state    = $state;
        $this->message  = $message;
        $this->code     = $code;
        $this->data     = $data;

        parent::__construct($message, $this->code);

        /*自动记录错误日志*/
        if(\IoaRunVariable::getLibDebugState()){
            $logger = new Logger();
            $logger->exceptionLog($this);
        }
    }

    /**
     * 返回错误状态
     * @return mixed
     */
    public function getState(){
        return $this->state;
    }

    /**
     * 返回相关数据
     * @return mixed
     */
    public function getData(){
        return $this->data;
    }


    /**
     * 读取错误信息
     * @return string
     */
    public function getMsg(){
        return $this->message;
    }

    /*将异常对象映射为ResultEntity对象*/
    public static function getErrResult($e){
        if($e instanceof \Exception){
            $result = new ResultEntity();

            $result->setResultCode($e->getCode());
            $result->setMsg($e->getMessage());

            if(\IoaRunVariable::getLibDebugState()){
                $result->setData(array(
                    "file"          => $e->getFile(),
                    "line"          => $e->getLine(),
                    "TraceAsString" => $e->getTraceAsString(),
                ));
            }

            /*当异常类为CommonException时，含有getData方法*/
            if(method_exists($e, "getData")){
                $data           = $result->getData();
                $data["data"]   = $e->getData();

                $result->setData($data);
            }

            if(method_exists($e, "getMsg")){
                $msg = $e->getMsg();
                $result->setMsg($msg);
            }

            return  $result;
        }

        return null;
    }
}