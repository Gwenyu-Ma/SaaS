<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-11-29
 * Time: 13:38
 */

namespace Plugs\IOA\EC\EC;


use Plugs\IOA\Conf\IErrCode;
use Plugs\IOA\Conf\IErrMsg;
use Plugs\IOA\EC\Driver\EcEC;
use Plugs\IOA\Library\Exception\ArgumentException;
use Plugs\IOA\Library\Util\EmailUtil;
use Plugs\IOA\Library\Util\MobileUtil;

/**
 * 团队相关的ec调用的类
 * Class OrgEC
 * @package IOA\EC\EC
 */
class OrgEC extends EcEC {
    /**
     * 创建团队和管理员
     * @param $orgName  团队名称
     * @param $userName 管理员用户名
     * @return array
     * @throws ArgumentException
     */
    public function createOrg($orgName, $userName){
        if(isEmpty($orgName)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        if(isEmpty($userName)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        if(!(MobileUtil::checkPhone($userName) || EmailUtil::isEmail($userName))){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_FORMAT_ERROR."，必须是手机号或者邮箱", IErrCode::I_ARGS_FORMAT_ERROR);
        }

        $data = array(
            "orgName"  => $orgName,
            "account"  => $userName,
        );

        /*返回数据*/
        $result = $this->callEc("registerAdmin", $data, array(
            "platformUserId",
            "platformOrgId"
        ));

        /*进行数据转换*/
        $result = $this->getFinalResult($result, array(
            "platformOrgId"     => "orgCode",
            "platformUserId"    => "userId",
        ));

        return $result;
    }

    /**
     * 实现修改团队名称
     * @param $orgCode  团队code
     * @param $orgName  团队名称
     * @return array
     * @throws ArgumentException
     */
    public function changeOrgName($orgCode, $orgName){
        if(isEmpty($orgCode)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        if(isEmpty($orgName)){
            throw new ArgumentException(false, IErrMsg::I_ERROR_ARG_VALUE_EMPTY, IErrCode::I_ARGS_VALUE_EMPTY);
        }

        $data = array(
            "platformOrgId"  => $orgCode,
            "orgName"        => $orgName,
        );

        /*返回数据*/
        $result = $this->callEc("modifyOrgName", $data);

        return $result;
    }
}