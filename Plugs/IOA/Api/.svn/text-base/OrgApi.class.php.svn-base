<?php
namespace Plugs\IOA\Api;
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-11-25
 * Time: 13:44
 */
use Plugs\IOA\Conf\IIoaSdkVariable;
use Plugs\IOA\EC\EC\OrgEC;
use Plugs\IOA\Library\Entity\ResultEntity;
use Plugs\IOA\Library\Exception\CommonException;

/**
 * Class Org
 * @package Api
 */
class OrgApi extends BasicApi{
    private $orgEC;

    public function __construct(){
        $this->orgEC = new OrgEC();
    }

    /**
     * 创建团队和管理员
     * @param string $orgName   团队名称
     * @param string $userName  管理员用户名
     * @return ResultEntity     若成功返回团队code和管理员id
     */
    protected function createOrg($orgName = IIoaSdkVariable::defaultOrgName, $userName = ""){
        $result = new ResultEntity();

        try{
            $orgName = isEmpty($orgName) ? IIoaSdkVariable::defaultOrgName : $orgName;

            /*调用接口进行通讯*/
            $result = $this->orgEC->createOrg($orgName, $userName);
        } catch (\Exception $e){
            $result = CommonException::getErrResult($e);
        } finally{
            /*执行后置操作*/
            $this->__after($result);

            return $this->dataOutput($result);
        }
    }

    /**
     * 修改团队名称
     * @param string $orgCode       团队号
     * @param string $newOrgName    新的团队名称
     * @return array|ResultEntity|null
     */
    protected function changeOrgName($orgCode = "", $newOrgName = ""){
        $result = new ResultEntity();

        try{
            /*调用接口进行通讯*/
            $result = $this->orgEC->changeOrgName($orgCode, $newOrgName);

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