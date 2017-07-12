<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-11-29
 * Time: 13:37
 */

namespace Plugs\IOA\EC\EC;


use Plugs\IOA\Conf\IErrCode;
use Plugs\IOA\Conf\IErrMsg;
use Plugs\IOA\EC\Driver\EcEC;
use Plugs\IOA\Library\Exception\ArgumentException;

/**
 * 用户相关的调用ec的类
 * Class MemberEC
 * @package IOA\EC\EC
 */
class MemberEC extends EcEC {
    /**
     * 团队加入成员
     * @param $orgCode      团队code
     * @param $userName     成员用户名
     * @return array
     * @throws ArgumentException
     */
    public function addOrgMember($orgCode, $userName){
        if(isEmpty($orgCode)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        if(isEmpty($userName)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        $data = array(
            "platformOrgId"  => $orgCode,
            "account"       => $userName,
        );

        /*返回数据*/
        $result = $this->callEc("registerUser", $data, array(
            "platformUserId",
            "platformOrgId",
        ));

        /*进行数据转换*/
        $result = $this->getFinalResult($result, array(
            "platformOrgId"     => "orgCode",
            "platformUserId"    => "userId",
        ));

        return $result;
    }


    /**
     * 成员变更团队
     * @param $userId       成员在爱办公的唯一标识id
     * @param $orgCode      原有的团队code
     * @param $newOrgCode   新的团队code
     * @return array
     * @throws ArgumentException
     */
    public function changeMemberOrg($userId, $orgCode, $newOrgCode){
        if(isEmpty($userId)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        if(isEmpty($newOrgCode)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        $data = array(
            "platformUserId"    => $userId,
            "platformOrgId"     => $orgCode,
            "newPlatformOrgId"  => $newOrgCode,
        );

        /*返回数据*/
        $result = $this->callEc("changeOrg", $data, array(
            "platformUserId",
            "platformOrgId"
        ));

        return $result;
    }

    /**
     * 退出团队
     * @param $orgCode  团队code
     * @param $userId   用户id
     * @return array
     * @throws ArgumentException
     */
    public function outOfOrg($orgCode, $userId){
        if(isEmpty($orgCode)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        if(isEmpty($userId)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        $data = array(
            "platformUserId"=> $userId,
            "platformOrgId"  => $orgCode,
        );

        /*返回数据*/
        $result = $this->callEc("quitOrg", $data);

        return $result;
    }


    /**
     * 更换管理员
     * @param string $orgCode   团队code
     * @param string $userId    用户id
     * @param string $newUserId   新用户id
     * @return \IOA\Library\Entity\ResultEntity
     * @throws ArgumentException
     */
    public function changeAdmin($orgCode = "", $userId = "", $newUserId = ""){
        if(isEmpty($orgCode)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        if(isEmpty($userId)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        if(isEmpty($newUserId)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        $data = array(
            "platformOrgId"     => $orgCode,
            "platformUserId"    => $userId,
            "newPlatformUserId" => $newUserId
        );

        /*返回数据*/
        $result = $this->callEc("modifySuperAdmin", $data);

        return $result;
    }
}