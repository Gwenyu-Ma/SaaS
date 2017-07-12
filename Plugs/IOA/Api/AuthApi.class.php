<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-11-25
 * Time: 13:58
 */

namespace Plugs\IOA\Api;
use Plugs\IOA\Conf\IErrCode;
use Plugs\IOA\Conf\IErrMsg;
use Plugs\IOA\EC\EC\AuthEC;
use Plugs\IOA\Library\Entity\ResultEntity;
use Plugs\IOA\Library\Exception\CommonException;
use Plugs\IOA\Library\Util\SequenceUtil;

/**
 * 登录认证
 * Class Auth
 * @package Api
 */
class AuthApi extends BasicApi{
    private $authEC;

    public function __construct(){
        $this->authEC = new AuthEC();
    }

    /**
     * 获取rsa签名
     * @param array $params 签名参数
     * @return ResultEntity|null
     */
    protected function getRsaSign($params = array()){
        $result = new ResultEntity();

        try{
            $sign = $this->authEC->getRsaSign($params);

            $result->setStatus(true);
            $result->setResultCode(IErrCode::I_SUCCESS);
            $result->setMsg(IErrMsg::I_SUCCESS);
            $result->setData(array(
                "sign" => $sign
            ));
        } catch (\Exception $e){
            $result = CommonException::getErrResult($e);
        } finally{
            /*执行后置操作*/
            $this->__after($result);

            return $this->dataOutput($result);
        }
    }

    /**
     * 团队管理员/成员登录认证，若登录成功后返回token
     * @param $userId  string 用户id
     * @return ResultEntity|null
     */
    protected function getToken($userId = ""){
        $result = new ResultEntity();

        try{
            /*调用接口进行通讯*/
            $result = $this->authEC->getToken($userId);
        } catch (\Exception $e){
            $result = CommonException::getErrResult($e);
        } finally{
            /*执行后置操作*/
            $this->__after($result);

            return $this->dataOutput($result);
        }
    }

    /**
     * 登录认证
     * @param $orgCode string 组织号
     * @param $userId  string 用户id
     * @return array
     */
    protected function getLoginArgs($orgCode = "", $userId = ""){
        $result = new ResultEntity();

        try{
            /*调用接口进行通讯*/
            $result = $this->authEC->getLoginArgs($orgCode, $userId);
        } catch (\Exception $e){
            $result = CommonException::getErrResult($e);
        } finally{
            /*执行后置操作*/
            $this->__after($result);

            return $this->dataOutput($result);
        }

        return $result;
    }
}