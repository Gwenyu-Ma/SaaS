<?php  
namespace Lib\Util;

use Plugs\IOA\Api\AuthApi;
use Plugs\IOA\Api\MemberApi;
use Plugs\IOA\Api\OrgApi;
use Plugs\IOA\Library\Encrypt\RSA;
use \Lib\Util\Log;
use Lib\Store\RedisCluster as Redis;
use \Lib\Store\Mysql;
use Lib\Store\MysqlWriteCluster as MW;
use Lib\Model\RedisDataManager as rds;

class Ioa
{
    public  static $is_require;
    public static function init(){

        if(self::$is_require !== null){
            return ;
        }else{
            self::$is_require = 1;
            require_once(__DIR__.'/../../Plugs/IOA/IoaSdk.php');
        }
    }

    /*
    *$phone:管理员手机号 或者邮箱
    *orgName:团队名称,userName管理员用户名
    *return：json
    *func:创建团队和团队管理员
    */
    public static  function createIoaOrg( $userName,$orgName='瑞星安全云' ){
        self::init();
        $org    = new OrgApi();
        $jsonIoa = $org->createOrg( $orgName,$userName);
        return json_decode( $jsonIoa );
    }

    /*
    *$orgCode:团队code
    *$newOrgName:新的团队名称
    *func：修改团队名称
    */
    public static function changeIoaOrgName( $orgCode,$newOrgName ){
        self::init();
        $org        = new OrgApi();
        $jsonIoa = $org->changeOrgName($orgCode, $newOrgName);
        return json_decode( $jsonIoa );
    }

    /*
     *param:$orgCode:团队新code
    *param:$userId 管理员id
    *param:newUserId 管理员新的id
    *func:修改管理员帐号
     */
    public static function modifyIoaAdmin( $orgCode, $userId, $newUserId ){
        self::init();
        $member     = new MemberApi();
        $jsonIoa     = $member->changeAdmin($orgCode, $userId, $newUserId);

        return json_decode( $jsonIoa );

    }

    /*
    *$orgCode:团队code
    *$userName:新加入的团队成员名称
    *func：团队成员加入
     */
    public static function addIoaOrgMember( $orgCode,$userName ){
        self::init();
        $member     = new MemberApi();
        $jsonIoa     = $member->addOrgMember($orgCode, $userName);
        return json_decode( $jsonIoa );
    }

    /*
    *$userId:成员在爱办公的唯一标识
    *$orgCode:新的团队code
    *$newOrgCode:新团队code
    *func:成员更换团队
     */
   public static function changeIoaMemberOrg( $userId, $orgCode, $newOrgCode ){
       self::init();
       $member     = new MemberApi();
       $jsonIoa     = $member->changeMemberOrg($userId, $orgCode, $newOrgCode);
       return json_decode( $jsonIoa );
    }

    /*
    *$orgCode:团队code
    *$userId:成员在ioa的唯一标识id
    *func:团队成员退出团队
     */
    public static function  outIoaOrg( $orgCode,$userId ){
        self::init();
        $member     = new MemberApi();
        $jsonIoa     = $member->outOfOrg($orgCode,$userId);
        return json_decode( $jsonIoa );
    }

    /*
    *@params: array($orgCode,$userId)
    *func:获取请求签名  用来校验签名
     */
   public static function getIoaRsaSign( $orgCode,$userId ){
       self::init();
       $auth       = new AuthApi();
       /*
        * Array (
        * [requestNo] => 82529102-A666-4119-99C4-770219524EAA
        * [cmdType] => redirectDoc
        * [requestTime] => 20161216162254
        * [platformNo] => rising
        * [requestSign] => dfgi12uEk+PQvYrQBT8wtZXNBlqHCrx+WIk9eCLL8ErcqKzT5b/fH8r+SujOd/dZ3UgKOzAdKQNbKFhffpik+09obpFN6yDJbRwpf65qNbXPkw4MrPJhCRDewBk8i0ENLMJ+iKYTNTxXbXN46bLhKOBWwg8/KPjeMBJEbm+3tKaRs4WnWSg4AUMgNfJHj03w8hNEnhSgv3IdatNf7gURAeeZ7Rk2YvIELpRpnrTX+mpxRVSbk9MO3zMEv7Okp+yxEfzq1MUVWukxjnY4iDDVC6hsYlnhJier0miAOpra+246e/jw8lpt8ZvS4ic+HHh/V2WtQ8SXkN0ba3/KWOr+kQ==
        * [platformUserId] => 4048f5f3ced7487bbc6d480957469591
        * [platformOrgId] => 596147be871144519c2cee42be436326 )
        */
       return $auth->getLoginArgs($orgCode, $userId);


    }

    /*
    *$sguid:客户端唯一表示
    *$eid:企业eid
    *func:团队管理员/成员获取认证令牌
     */
    public static function getIoaToken( $eid,$sguid ){
        self::init();
        $orgid = rds::getIoaorgid($eid);
        $userid = rds::getIoaUserid($eid,$sguid);
        if( empty( $userid ) ){
            $aUserid = Mysql::getRow("SELECT userid,orgid FROM eid_orgid where eid='$eid'");
            $userid = $aUserid['userid'];
            $orgid = $aUserid['orgid'];
            rds::setIoaorgid($eid,$orgid);
            rds::saveIoaUserid( $eid, $sguid, $userid );
        }
        //$orgid = self::getOrgidByEid( $eid );

        $auth       = new AuthApi();
        $jsonIoa     = $auth->getToken($userid);
        $objIoa = json_decode( $jsonIoa );
        if($objIoa->resultCode !== '0000'){
            return 1;
        }else{
            return array('token' =>$objIoa->data->token,'userid'=>$aUserid['userid'],'orgid'=>$orgid);
        }
    }

    //以下方法是终端调用

    public static function getEidAndUseridBySguid( $sguid ){
        $eid_userid = rds::getSguidMapEidUserid( $sguid );
        if(empty( $eid_userid )){
            $res =  Mysql::getRow("SELECT eid,userid FROM sguid_userid where sguid='$sguid'");
            rds::setSguidMapEidUserid( $sguid,$res['eid'],$res['userid'] );
        }else{
            $res = array();
            $arr = explode(':',$eid_userid);
            $res['eid'] = $arr[0];
            $res['userid'] = $arr[1];
        }

        return $res;
    }

    public static function getOrgidByEid( $eid ){
        $orgid = rds::getIoaorgid($eid);
        if(empty($orgid)){
            $aOrgid = Mysql::getRow("SELECT orgid FROM eid_orgid where eid='$eid'");
            if(empty($aOrgid['orgid'])){
                return;
            }
            $orgid = $aOrgid['orgid'];
            rds::setIoaorgid($eid,$orgid);
        }
        return $orgid;
    }

    public static function updateSguidInfo( $sguid,$eid ){
        Mysql::exec("UPDATE sguid_userid SET eid='$eid' WHERE sguid='$sguid' ");
    }

    public static function getDiskAccess( $eid ){
        $disk_set = rds::getIoaCenterDisk( $eid );
        if(empty($disk_set)){
            $res =  Mysql::getRow("SELECT disk_set FROM eid_orgid where eid='$eid'");
            $disk_set = $res['disk_set'];
            rds::setIoaCenterDisk($eid,$disk_set);
        }
        return $disk_set;
    }

    public static function terminalAddMember( $sguid,$eid ){
        $current_time = date("Y-m-d H:i:s");
        $orgid = self::getOrgidByEid( $eid );
        if(empty($orgid)){
            return false;
        }
        $ObjMember = self::addIoaOrgMember( $orgid,$sguid );
        //print_r($ObjMember);
        $userId = $ObjMember->data->userId;
        if( empty($userId)){
           define('REDIS_SGUID_QUEUE', 'ioa_sguid_queue');//客户端sguid中需要生成IOA的userid {"eid": "","sguid": ""}
            Log::add("getIoaUseridError",array("msg" => 'ioa userid is null'));
           Redis::lPush(REDIS_SGUID_QUEUE, json_encode(array('eid'=>$eid,'sguid'=>$sguid)));
            return false;
        }
        rds::saveIoaUserid( $eid, $sguid, $userId );

        $disk_access = self::getDiskAccess($eid);
        $disk_access > 0?$disk_access=$disk_access:$disk_access=0;
        Mysql::exec("INSERT INTO sguid_userid (sguid,eid,userid,add_time,disk_access) VALUES ('$sguid','$eid','$userId','$current_time',$disk_access)");
        rds::setSguidMapEidUserid( $sguid,$eid,$userId);
        return true;
    }

    
}