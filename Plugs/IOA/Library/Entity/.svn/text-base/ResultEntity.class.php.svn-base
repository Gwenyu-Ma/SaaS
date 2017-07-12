<?php
namespace Plugs\IOA\Library\Entity;

/**
 * APK统一实体类
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-06-15
 * Time: 15:18
 */
class ResultEntity extends BasicEntity {
    /*返回结果*/
    private $status  = false;

    /*提示消息*/
    private $msg    = "";

    /*返回code*/
    private $resultCode= "";

    /*返回数据*/
    private $data   = array();

    public function __construct($status = false, $msg = "", $resultCode = ""){
        $this->status       = $status;
        $this->msg          = $msg;
        $this->resultCode   = $resultCode;
    }

    public function getStatus(){
        return $this->status;
    }

    public function setStatus($status = false){
        $this->status = $status;
    }

    public function getResultCode(){
        return $this->resultCode;
    }

    public function setResultCode($resultCode){
        $this->resultCode = $resultCode;
    }

    public function getMsg(){
        return $this->msg;
    }

    public function setMsg($msg = ""){
        $this->msg = $msg;
    }

    public function getData(){
        return $this->data;
    }

    public function setData($data){
        $this->data = $data;
    }
}