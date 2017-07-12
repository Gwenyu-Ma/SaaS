<?php
namespace Plugs\IOA\EC\Driver;
use Plugs\IOA\Conf\IErrCode;
use Plugs\IOA\Conf\IIoaSdkVariable;
use Plugs\IOA\Library\Exception\HttpException;
use Plugs\IOA\Library\Logger\Logger;

/**
 * http通讯驱动类
 * Class EC
 * @package IOA\EC\Driver
 */
class EC {
    private $hostUrl = IIoaSdkVariable::gateway;

    private $headers;

    protected function __construct(){
        $this->headers = array('content-type: application/x-www-form-urlencoded;charset=UTF-8;');
    }

    /**
     * 接口实例化
     * @return EC
     */
    public static function getInstance() {
        static $_instance;

        if(!isset($_instance)){
            $obj	=	new EC();
            $_instance	=	$obj;
        }

        return $_instance;
    }

    /**
     * 发起http请求
     * @param $postData 请求数据
     * @return mixed
     * @throws HttpException
     * @throws \HttpException
     */
    public function curl($postData){
        $curlPostData = $this->getCurlPostData($postData);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->hostUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPostData);

        $response = curl_exec($ch);

        /*记录ec请求和响应的日志*/
        $this->logEcRequestData($postData, $response);

        /*网络通讯失败*/
        if (curl_errno($ch)) {
            throw new HttpException(false, curl_error($ch), IErrCode::I_NETWORK_FAIL);
        } else{
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new HttpException(false, "http通讯返回错误码：".$httpStatusCode, IErrCode::I_NETWORK_FAIL);
            }
        }

        curl_close($ch);

        return $response;
    }

    /**
     * 转换数据格式
     * @param $postData 请求数据数组
     * @return string
     */
    private function getCurlPostData($postData){
        $contentType    =  $this->headers[0];
        $returnData     = "";

        if(strripos($contentType, "application/json") !== false){
            $returnData = json_encode($postData);
        } else if(strripos($contentType, "application/x-www-form-urlencoded") !== false){
            foreach ($postData as $key => $value){
                $returnData .= "$key=" . urlencode($value) . "&";
            }

            return $returnData;
        }

        return $returnData;
    }

    /**
     * 记录ec请求和响应的日志
     * @param $requestData  请求数据
     * @param $responseData 响应数据
     */
    private function logEcRequestData($requestData, $responseData){
        try{
            $logger = new Logger();
            $logger->apiLogFile($requestData, $responseData, $source="IOA");
        } catch (\Exception $e){

        }
    }
}