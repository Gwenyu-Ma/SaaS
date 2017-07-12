<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-11-25
 * Time: 13:58
 */

namespace Plugs\IOA\Api;
use Plugs\IOA\EC\EC\MemberEC;
use Plugs\IOA\Library\Entity\ResultEntity;
use Plugs\IOA\Library\Exception\CommonException;

/**
 * 组织成员操作
 * Class Member
 * @package Api
 */
class MemberApi extends BasicApi {
    /*ec通讯对象*/
    private $memberEC;

    public function __construct(){
        $this->memberEC = new MemberEC();
    }

    /**
     * 团队初次加入成员
     * @param string $orgCode      团队code
     * @param string $userName  成员用户名
     * @return \IOA\EC\EC\ResultEntity|ResultEntity|null
     */
    protected function addOrgMember($orgCode = "", $userName = ""){
        $result = new ResultEntity();

        try{
            /*调用接口进行通讯*/
            $result = $this->memberEC->addOrgMember($orgCode, $userName);
        } catch (\Exception $e){
            $result = CommonException::getErrResult($e);
        } finally{
            /*执行后置操作*/
            $this->__after($result);

            return $this->dataOutput($result);
        }
    }

    /**
     * 成员变更团队
     * @param string $userId       成员在爱办公的唯一标识id
     * @param string $orgCode      原有的团队code
     * @param string $newOrgCode   新的团队code
     * @return \IOA\EC\EC\ResultEntity|ResultEntity|null
     */
    protected function changeMemberOrg($userId = "", $orgCode = "",$newOrgCode = ""){
        $result = new ResultEntity();

        try{
            /*调用接口进行通讯*/
            $result = $this->memberEC->changeMemberOrg($userId, $orgCode, $newOrgCode);

            /*获取已经解析的结果*/


        } catch (\Exception $e){
            $result = CommonException::getErrResult($e);
        } finally{
            /*执行后置操作*/
            $this->__after($result);

            return $this->dataOutput($result);
        }
    }


    /**
     * 成员退出团队
     * @param string $orgCode    团队号
     * @param string $userId  成员在爱办公的唯一标识id
     * @return array|ResultEntity
     */
    protected function outOfOrg($orgCode = "", $userId = ""){
        $result = new ResultEntity();

        try{
            /*调用接口进行通讯*/
            $result = $this->memberEC->outOfOrg($orgCode, $userId);

            /*获取已经解析的结果*/
        } catch (\Exception $e){
            $result = CommonException::getErrResult($e);
        } finally{
            /*执行后置操作*/
            $this->__after($result);

            return $this->dataOutput($result);
        }
    }


    /**
     * 更换管理员
     * @param string $orgCode   团队code
     * @param string $userId    管理员id
     * @param string $newUserId  新管理员id
     * @return ResultEntity|null
     */
    protected function changeAdmin($orgCode = "", $userId = "", $newUserId = ""){
        $result = new ResultEntity();

        try{
            /*调用接口进行通讯*/
            $result = $this->memberEC->changeAdmin($orgCode, $userId, $newUserId);

            /*获取已经解析的结果*/
        } catch (\Exception $e){
            $result = CommonException::getErrResult($e);
        } finally{
            /*执行后置操作*/
            $this->__after($result);

            return $this->dataOutput($result);
        }
    }
}