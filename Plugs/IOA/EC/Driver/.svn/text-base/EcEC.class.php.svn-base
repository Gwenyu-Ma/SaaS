<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-07-20
 * Time: 20:43
 */

namespace Plugs\IOA\EC\Driver;
use Plugs\IOA\Conf\IErrCode;
use Plugs\IOA\Conf\IErrMsg;
use Plugs\IOA\Conf\IIoaSdkVariable;
use Plugs\IOA\Library\Encrypt\RSA;
use Plugs\IOA\Library\Entity\ResultEntity;
use Plugs\IOA\Library\Exception\ArgumentException;
use Plugs\IOA\Library\Exception\AuthException;
use Plugs\IOA\Library\Exception\HttpException;
use Plugs\IOA\Library\Util\DateUtil;
use Plugs\IOA\Library\Util\SequenceUtil;

/**
 * ec接口通讯类，实现以下功能
 * 1、请求数据的封装
 * 2、发起http通讯
 * 3、解析返回的数据
 * Class EcEC
 * @package Plugs\IOA\EC\Driver
 */
class EcEC {
    /*返回数据*/
    protected $returnData    = null;

    /*接入的ec接口名称*/
    protected $cmdType;

    /*返回参数key名称数组*/
    protected $returnKey;

    /**
     * 进行http请求, 并返回数据
     * @param string $cmdType 接入接口名称
     * @param array $postData 请求数据
     * @param $returnKey 返回参数key
     * @return ResultEntity
     */
    protected function callEc($cmdType = "", $postData = array(), $returnKey = null){
        $this->cmdType      = $cmdType;
        $this->returnKey    = $returnKey;

        $requestData= $this->getRequestData($postData);
        $instance   = EC::getInstance();
        $returnData = $instance->curl($requestData);
        $result     = $this->parseResult($returnData);

        return $result;
    }

    /**
     * 生成接口请求参数数组
     * @param $postData 接口请求参数
     * @return array
     */
    protected function getRequestData($postData){
        /*获取每次请求的唯一标识*/
        $requestNo      = SequenceUtil::getGuid();

        /*获取调用接口*/
        $cmdType        = $this->cmdType;

        /*获取调用方的平台标识码*/
        $platformNo     = IIoaSdkVariable::platform;

        /*获取接口发起请求时间*/
        $requestTime    = DateUtil::getCurrentUTCDateTime("YmdHis");

        /*生成签名*/
        $data           = array(
            "requestNo"     => $requestNo,
            "cmdType"       => $cmdType,
            "requestTime"   => $requestTime,
            "platformNo"    => $platformNo
        );

        /*生成签名*/
        $requestSign = $this->getRsaSign(array_merge($data,$postData));

        /*生成请求参数*/
        $data           = array_merge($data, array(
            "requestSign" => $requestSign
        ),$postData);

        return $data;
    }

    /**
     * 生成签名，签名内容生成如下：
     * 1、签名是有各个参数拼接而成
     * 2、若存在参数为null，则替换成空字符串
     * 3、对拼接而成的参数字符串进行md5加密码
     * @param $params
     * @return string
     */
    protected function getRsaSign($params) {
        /*检查并替换value为null的元素*/
        $params       = array_map(function($value){
            return is_null($value) ? "" : $value;
        }, $params);

        //var_dump($params);
        $stringToBeSigned = implode("", $params);

        /*加密请求参数*/
        //echo '$stringToBeSigned='.$stringToBeSigned.'<br>';

        $stringToBeSigned = md5($stringToBeSigned);
        //echo '$stringToBeSigned='.$stringToBeSigned.'<br>';

        $rsa            = new RSA();
        $requestSign    = $rsa->rsaPublicEncrypt($stringToBeSigned);

        return $requestSign;
    }

    /**
     * 解析ec返回数据,对返回数据进行验签操作
     * @param $returnData       ec返回的数据
     * @return ResultEntity     生成结果
     * @param $returnData
     * @return ResultEntity
     * @throws ArgumentException
     * @throws AuthException
     * @throws HttpException
     */
    private function parseResult($returnData){
        if(!isEmpty($returnData)){
            //echo $returnData.'<br>';
            if(is_string($returnData)){
                $returnData = json_decode($returnData,true);
            }

            /*返回数据是否解析正确*/
            if(!is_array($returnData)){
                throw new ArgumentException(false, IErrMsg::I_ERROR_IOA_RETURN_DATA_PARSE_WRONG, IErrCode::I_IOA_RETURN_DATA_PARSE_WRONG);
            }

            $responseSign   = $returnData["responseSign"];
            $resultCode     = $returnData["resultCode"];
            $resultMsg      = $returnData["resultMsg"];

            //var_dump($returnData);
            /*对返回签名进行验签操作*/
            $verifyData     = $this->getNeedVerifyData($returnData);
            $rsa            = new RSA();

            $verifyResult   = $rsa->rsaPublicVerify($verifyData,$responseSign);

            if($verifyResult === false){
                throw new AuthException(false, IErrMsg::I_ERROR_RSA_VERIFY_FAIL, IErrCode::I_RSA_SIGN_VERIFY_FAIL);
            }

            $state      = $resultCode == "0000";
            $msg        = $resultMsg;

            /*移除不必要的借点*/
            $data = $returnData;
            unset($returnData["responseSign"]);
            unset($returnData["resultCode"]);
            unset($returnData["resultMsg"]);

            $result = new ResultEntity();
            $result->setResultCode($resultCode);

            $result->setMsg($msg);
            $result->setStatus($state);
            $result->setData($data);

            return $result;
        } else{
            throw new HttpException(false, IErrMsg::I_ERROR_IOA_RETURN_EMPTY, IErrCode::I_IOA_RETURN_DATA_EMPTY);
        }

    }

    /**
     * 拼接返回的参数值，用于签名验证
     * @param $returnData   返回参数
     * @return string
     */
    private function getNeedVerifyData($returnData){
        if(is_array($returnData)){
            $returnData = array_change_key_case($returnData, CASE_LOWER);
        }

        $responseNo = $returnData[strtolower("responseNo")];
        $platformNo = $returnData[strtolower("platformNo")];
        $resultCode = $returnData[strtolower("resultCode")];
        $resultMsg  = $returnData[strtolower("resultMsg")];

        $data       = $responseNo.$platformNo.$resultCode.$resultMsg;

        if(!is_null($this->returnKey)){
            foreach ($this->returnKey as $value){
                $value = strtolower($value);

                if(array_key_exists($value, $returnData)){
                    $data .= $returnData[$value];
                }
            }
        }

        $data = md5($data);

        return $data;
    }

    /**
     * 进行数组
     * @param $result   结果对象
     * @param $arrKey   映射key数组
     * @return mixed
     */
    protected function getFinalResult($result,$arrKey){
        if($result instanceof ResultEntity){
            $arrData = $result->getData();

            if($arrData){
                foreach ($arrKey as $key => $value){
                    if(array_key_exists($key, $arrData)){
                        $arrData[$value] = $arrData[$key];
                        unset($arrData[$key]);
                    }
                }

                $result->setData($arrData);
            }
        }

        return $result;
    }
}