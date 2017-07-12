<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-11-29
 * Time: 17:48
 */

namespace Plugs\IOA\Library\Encrypt;
use Plugs\IOA\Conf\IErrCode;
use Plugs\IOA\Conf\IErrMsg;
use Plugs\IOA\Library\Exception\AuthException;

/**
 * 实现rsa加密,解密机制
 * Class RSA
 * @package IOA\Library\Encrypt
 */
class RSA {
    /*签名类型*/
    public $signType = "RSA";

    /*编码格式*/
    private $charset = "utf-8";

    /*私钥文件路径*/
    private $privateKeyFilePath = "";

    /*公钥文件路径*/
    private $publicKeyFilePath = "";

    public function __construct(){
        $this->privateKeyFilePath   = \IoaRunVariable::getLibRoot().DIRECTORY_SEPARATOR."privatekey.pem";
        $this->publicKeyFilePath    = \IoaRunVariable::getLibRoot().DIRECTORY_SEPARATOR."publickey.pem";
    }

    /**
     * 生成rsa签名
     * @param $params   需要私钥加密的参数
     * @return string
     * @throws AuthException
     */
    public function rsaSign($params) {
        /*生成原始的签名内容*/
        $data       = $this->getSignContent($params);

        $privateKey = file_get_contents($this->privateKeyFilePath);
        $resource   = openssl_get_privatekey($privateKey);

        if(!$resource){
            throw new AuthException(false, IErrMsg::I_ERROR_RSA_PRIVATE_KEY_WRONG, IErrCode::I_RSA_PRIVATE_KEY_WRONG);
        }

        openssl_sign($data, $sign, $resource);

        openssl_free_key($resource);

        $sign = base64_encode($sign);

        return $sign;
    }

    /**
     * 私钥加密
     * @param $data     原始数据
     * @return string   返回经过base64后的签名
     * @throws AuthException
     */
    public function rsaPrivateEncrypt($data) {
        $chrText    = null;
        /*读取公钥文件*/
        $privateKey = file_get_contents($this->privateKeyFilePath);
        /*转换为openssl格式密钥*/
        $resource   = openssl_get_privatekey($privateKey);
        $blocks     = $this->splitCN($data, 0, 30, $this->charset);

        foreach ($blocks as $n => $block) {
            if (!openssl_private_encrypt($block, $chrText , $resource)) {
                throw new AuthException(false, openssl_error_string(), IErrCode::I_RSA_PRIVATE_ENCRYPT_WRONG);
            }

            $encodes[] = $chrText ;
        }

        $chrText = implode(",", $encodes);

        openssl_free_key($resource);

        $chrText = base64_encode($chrText);

        return $chrText;
    }

    /**
     * 私钥解密
     * @param $data 解密数据
     * @return string   返回解密后的结果
     * @throws AuthException
     */
    public function rsaDecrypt($data) {
        //读取私钥文件
        $priKey     = file_get_contents($this->privateKeyFilePath);
        //转换为openssl格式密钥
        $resource   = openssl_get_privatekey($priKey);

        $decodes    = explode(',', $data);
        $string     = "";
        $dcyCont    = "";

        foreach ($decodes as $n => $decode) {
            if (!openssl_private_decrypt($decode, $dcyCont, $resource)) {
                throw new AuthException(false, openssl_error_string(), IErrCode::I_RSA_PUBLIC_ENCRYPT_WRONG);
            }
            $string .= $dcyCont;
        }

        openssl_free_key($resource);

        return $string;
    }

    /**
     * 公钥加密
     * @param $data     原始数据
     * @return string   返回经过base64后的签名
     * @throws AuthException
     */
    public function rsaPublicEncrypt($data) {
        $chrText    = null;
        /*读取公钥文件*/
        $pubKey     = file_get_contents($this->publicKeyFilePath);
        /*转换为openssl格式密钥*/
        $resource   = openssl_get_publickey($pubKey);
        $blocks     = $this->splitCN($data, 0, 30, $this->charset);

        foreach ($blocks as $n => $block) {
            $encryptState = openssl_public_encrypt($block, $chrText, $resource);

            if (!$encryptState) {
                throw new AuthException(false, openssl_error_string(), IErrCode::I_RSA_SIGN_GENERATE_FAIL);
            }

            $encodes[] = $chrText;
        }

        $chrText = implode("", $encodes);

        if(!isEmpty($this->publicKeyFilePath)){
            openssl_free_key($resource);
        }

        $chrText = base64_encode($chrText);

        return $chrText;
    }

    /**
     * 公钥解密
     * @param $data     原始数据
     * @return string   返回经过base64后的签名
     * @throws AuthException
     */
    public function rsaPublicDecrypt($data) {
        /*读取公钥文件*/
        $pubKey     = file_get_contents($this->publicKeyFilePath);
        /*转换为openssl格式密钥*/
        $resource   = openssl_get_publickey($pubKey);

        $decodes    = explode(',', $data);
        $string     = "";
        $dcyCont    = "";

        foreach ($decodes as $n => $decode) {
            if (!openssl_public_decrypt($decode, $dcyCont, $resource)) {
                throw new AuthException(false, openssl_error_string(), IErrCode::I_RSA_PUBLIC_DECRYPT_WRONG);
            }
            $string .= $dcyCont;
        }

        openssl_free_key($resource);

        return $string;

    }

    /**
     * 公钥验签操作
     * @param $data     要进行公钥加密的数据字符串
     * @param $sign     返回的签名内容
     * @return bool
     * @throws AuthException
     */
    public function rsaPublicVerify($data, $sign) {
        /*读取公钥文件*/
        $pubKey = file_get_contents($this->publicKeyFilePath);

        /*转换为openssl格式密钥*/
        $resource = openssl_get_publickey($pubKey);

        if(!$resource){
            throw new AuthException(false, IErrMsg::I_ERROR_RSA_PUBLIC_KEY_WRONG, IErrCode::I_RSA_PUBLIC_KEY_WRONG);
        }

        $result = (bool)openssl_verify($data, base64_decode($sign), $resource,OPENSSL_ALGO_MD5);

        openssl_free_key($resource);

        return $result;
    }

    /**
     * 生成签名内容吗，签名内容生成如下：
     * 1、签名内容是有各个参数拼接而成
     * 2、若存在参数为null，则替换成空字符串
     * 3、对拼接而成的参数字符串进行md5加密码
     * @param $params
     * @return string
     */
    protected function getSignContent($params) {
        /*检查并替换value为null的元素*/
        $params       = array_map(function($value){
            return is_null($value) ? "" : $value;
        }, $params);

        $stringToBeSigned = implode("", $params);

        /*加密请求参数*/
        $stringToBeSigned = md5($stringToBeSigned);

        return $stringToBeSigned;
    }

    /**
     * 将字符串按照长度分割成数组
     * @param $cont     原始字符串
     * @param int $n    起始位置
     * @param $subnum   分割的子字符串长度
     * @return array    返回数组
     */
    function splitCN($cont, $n = 0, $subnum) {
        $arrr = array();

        for ($i = $n; $i < strlen($cont); $i += $subnum) {
            $res = $this->subCNchar($cont, $i, $subnum, $this->charset);

            if (!empty ($res)) {
                $arrr[] = $res;
            }
        }

        return $arrr;
    }

    /**
     * 中文分割符
     * @param $str          字符串
     * @param int $start    起始位置
     * @param $length       分割长度
     * @param $charset      字符串编码
     * @return string       得到的字符串
     */
    function subCNchar($str, $start = 0, $length, $charset) {
        if (strlen($str) <= $length) {
            return $str;
        }

        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
        return $slice;
    }
}