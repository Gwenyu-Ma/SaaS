<?php
namespace IOA\Test;
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-11-29
 * Time: 15:44
 */
use IOA\Api\Auth;
use IOA\Api\AuthApi;
use IOA\Api\MemberApi;
use IOA\Api\OrgApi;
use IOA\Library\Encrypt\RSA;

class Test {
    public function createOrg(){
        $orgList = array(
            array(
                "IOA",
                "15880214739",
            ),
        );

        $orgCodeList = array(

        );

        foreach ($orgList as $org){
            $orgName    = $org[0];
            $userName   = $org[1];

            $org        = new OrgApi();
            $orgCode    = $org->createOrg($orgName, $userName);

            array_push($orgCodeList, $orgCode);
            break;
        }

        //echo '创建团队<br>';
        var_dump($orgCodeList);

        return $orgCodeList;
    }

    public function changeOrgName(){
        $orgCode    = "cb97607ced694fcf8daf472797c5f958";
        $newOrgName = "IOA_CP";

        $org        = new OrgApi();
        $result     = $org->changeOrgName($orgCode, $newOrgName);

        //echo '修改团队名称<br>';
        var_dump($result);
    }

    public function addOrgMember(){
        $userList = array(
            array(
                "a23f5571fc0040f6ab762e40b19627c6",
                "290579856@qq.com",
            ),
        );

        $userIdList = array(

        );

        foreach ($userList as $user){
            $orgCode    = $user[0];
            $userName   = $user[1];

            $member     = new MemberApi();
            $userId     = $member->addOrgMember($orgCode, $userName);

            array_push($userIdList, $userId);
        }

        //echo '创建用户<br>';
        var_dump($userIdList);

        return $userIdList;
    }

    public function changeAdmin(){
        $orgCode    = "a8fa153ab756444aad7ae05d6756a1b8";
        $userId     = "fd275b56560947ebba6054c7b94c0309";
        $newUserId = "3ee95b1897b54454ae7693ad33545621";

        //'orgCode' => string '6a18e061c89d4dad8e869de8aacdaa83' (length=32)
         // 'userId' => string '13d6f5b2eb11408ab9db89ab1f78b813' (length=32)

        $member     = new MemberApi();
        $result     = $member->changeAdmin($orgCode, $userId, $newUserId);
        //echo '更换管理员<br>';
        var_dump($result);

        return $result;
    }

    public function changeMemberOrg(){
        $userId     = "91dcf9b38a624f1d80889ee00ef31f74";
        $orgCode    = "cb97607ced694fcf8daf472797c5f958";
        $newOrgCode = "ac600b1262d7494ebfdda3425b121546";

        $member     = new MemberApi();
        $result     = $member->changeMemberOrg($userId, $orgCode, $newOrgCode);

        //echo '变更团队<br>';
        var_dump($result);
    }

    public function outOfOrg(){
        $orgCode    = "ac600b1262d7494ebfdda3425b121546";
        $userId     = "7c2d54eebfc5474685b008a97a842308";

        $member     = new MemberApi();
        $result     = $member->outOfOrg($orgCode, $userId);
        //echo '退出团队<br>';
        var_dump($result);
    }

    public function getRsaSign(){
        $orgCode    = "";
        $userId     = "";
        $auth       = new AuthApi();
        $result     = $auth->getRsaSign(array(
            $orgCode,
            $userId
        ));

		echo "----------------------------------";
        var_dump($result);
    }

    public function getToken(){
        $userId     = "ff17246072cb498ea783862d3d0caa0b";
        $auth       = new AuthApi();
        $result     = $auth->getToken($userId);

        //echo '获取token<br>';
        var_dump($result);
    }

    public function getLoginArgs(){
        $orgCode    = "cb97607ced694fcf8daf472797c5f958";
        $userId     = "ff17246072cb498ea783862d3d0caa0b";
        $auth       = new AuthApi();
        $result     = $auth->getLoginArgs($orgCode, $userId);

        //echo '获取token<br>';
        var_dump($result);
    }

    public function verify(){
        $responseSign = "OLoORjZCzTLZ1ThPs4MFpeh5jP76SIlJvBwLJex5a4ALNZfvDkQz+7tqETCIIGBB2neRauwgdDymv0jlDGEg8zjr587w6v44Dz7RkeJSm83g0BElkHP2q9Wvd0gTVuu9Tr+chS1lOTrVbtpHk9DH1lzx1oMZSiS6MXwRVEoq0Kc=";
        $data           = md5("9ecdc6c870c6468591a8cf2159ad3a6b");
        $rsa            = new RSA();
        $verifyResult   = $rsa->rsaPublicVerify($data, $responseSign);

        var_dump($verifyResult);
    }
}