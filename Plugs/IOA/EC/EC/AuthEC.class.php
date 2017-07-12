<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-08-22
 * Time: 20:45
 */

namespace Plugs\IOA\EC\EC;

use Plugs\IOA\Conf\IErrCode;
use Plugs\IOA\Conf\IErrMsg;
use Plugs\IOA\Conf\IIoaSdkVariable;
use Plugs\IOA\EC\Driver\EcEC;
use Plugs\IOA\Library\Entity\ResultEntity;
use Plugs\IOA\Library\Exception\ArgumentException;

/**
 * ec认证接口
 * Class AuthEC
 * @package IOA\EC\EC
 */
class AuthEC extends EcEC  {
    /**
     * 获取rsa签名
     * @param $data     参数数组
     * @return mixed|string 返回签名数据
     * @throws ArgumentException
     */
    public function getRsaSign($data){
        if(isEmpty($data)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_RSA_PARAM_NULL, IErrCode::I_RSA_DATA_RAW_EMPTY);
        }

        /*返回数据*/
        $sign = parent::getRsaSign($data) ;

        return $sign;
    }

    /**
     * 团队管理员/成员登录认证，若登录成功后返回token
     * @param $userId  用户id
     * @return array
     * @throws ArgumentException
     */
    public function getToken($userId){
        if(isEmpty($userId)) {
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        $data = array(
            "platformUserId"  => $userId,
        );

        /*返回数据*/
        $result = $this->callEc("authLogin", $data, array(
            "token"
        ));

        return $result;
    }

    /**
     * 登录认证
     * @param $orgCode  组织号
     * @param $userId   用户id
     * @return array
     * @throws ArgumentException
     */
    public function getLoginArgs($orgCode, $userId){
        if(isEmpty($orgCode)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        if(isEmpty($userId)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        $this->cmdType = "redirectDoc";

        $params = $this->getRequestData(array(
            "platformUserId"    => $userId,
            "platformOrgId"     => $orgCode
        ));

        $params = array_merge($params, array(
            "url" => IIoaSdkVariable::gateway
        ));

        $result = new ResultEntity();

        $result->setResultCode(IErrCode::I_SUCCESS);
        $result->setStatus(true);
        $result->setMsg(IErrMsg::I_SUCCESS);
        $result->setData($params);

        return $params;
    }
}