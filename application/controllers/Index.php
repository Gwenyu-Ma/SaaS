<?php
use Lib\Util\Crypt;
use Lib\Util\EmailQueue;
use Endroid\QrCode\QrCode;
use Lib\Util\Log;
use Lib\Util\Ioa;
use Lib\Store\RedisCluster as Redis;

use Lib\Model\RedisDataManager;
class IndexController extends MyController
{
    public function init()
    {
        parent::init();
        Yaf_Dispatcher::getInstance()->disableView();
        parent::includeFormConfig();
    }

    public function indexAction()
    {
        $this->disply('Index/index_new');
    }

    public function loginAction(){
        $this->disply('Index/login');
    }

    /**
     * 退出
     */
    public function logOutAction()
    {
        $model = new UserModel();
        $model->logout();
        $this->disply('Index/login');
    }

    /*
     * 注册
     */
    public function registerAction()
    {
        $this->disply('Index/registerEnterprise');
    }

    public function postloginAction()
    {
        // init counter
        if(empty($_SESSION['show_code'])){
            $_SESSION['show_code'] = 0;
        }
        $str_user_name = $this->param('useridname', '');
        $str_user_pwd = $this->param('userpwd', '');
        $str_user_code = $this->param('code', '');

        if( empty($str_user_name) || empty($str_user_pwd) ) {
            $this->notice("用户名，密码不能留空！");
            add_oplog('执行','1001',$str_user_name,null,null,1, '登录失败');
            return;
        }
        // verify code
        if($_SESSION['show_code'] >= 3 && strtolower($str_user_code) !== strtolower(@$_SESSION['captcha_keystring'])) {
            $this->notice("验证码错误！", 13);
            add_oplog('执行','1001',$str_user_name,null,null,1, '登录失败');
            return;
        }
        // query
        $arr_user_info = null;
        $model = new UserModel();
        if(\Tx\Is::email($str_user_name)){
            $arr_user_info =  $model->getUserNameInfo( $str_user_name, 1 );
        }elseif(Common::checkPhone($str_user_name)){
            $arr_user_info =  $model->getUserNameInfo( $str_user_name, 2 );
        }else{
            $_SESSION['show_code']++;
            if($_SESSION['show_code'] == 3){
                $this->notice("用户名或密码错误!", 3);
                add_oplog('执行','1001',$str_user_name,null,null,1, '登录失败');
                return;
            }
            $this->notice("用户名或密码错误!", 11);
            add_oplog('执行','1001',$str_user_name,null,null,1, '登录失败');
            return;
        }
        // not found
        if(empty($arr_user_info)){
            $_SESSION['show_code']++;
            if($_SESSION['show_code'] == 3){
                $this->notice("用户名不存在!", 3);
                add_oplog('执行','1001',$str_user_name,null,null,1, '登录失败');
                return;
            }
            $this->notice("用户名不存在!", 11);
            add_oplog('执行','1001',$str_user_name,null,null,1, '登录失败');
            return;
        }
        // password
        $str_pwd_md5 = Common::passMd5($str_user_pwd, $arr_user_info['Salt'], $encrypt = 'md5');
        if($arr_user_info['PWD'] != $str_pwd_md5 ) {
            $_SESSION['show_code']++;
            if($_SESSION['show_code'] == 3){
                $this->notice("密码错误!", 3);
                add_oplog('执行','1001',$str_user_name,null,null,1, '登录失败');
                return;
            }
            $this->notice("密码错误!", 11);
            add_oplog('执行','1001',$str_user_name,null,null,1, '登录失败');
            return;
        }
        // active?
        if( $arr_user_info['Status']!=1 ) {
            $this->notice("用户信息不存在，请注册！", 14);
            add_oplog('执行','1001',$str_user_name,null,null,1, '登录失败');
            return;
        }
        // ok
        $_SESSION['show_code'] = null;
        $_SESSION['UserInfo'] = $arr_user_info;
        $groupmodel = new GroupModel();
        $GroupGroupID = $groupmodel->getGlocbalGroupID($arr_user_info['EID']);
        $_SESSION['GroupInfo']['GlobalGroupID'] = $GroupGroupID;
        $data = array('location'=>array('m'=>'Manage','a'=>'index'));
        if($model->isFirstLogin($arr_user_info['UserID'])){
            $_SESSION['UserInfo']['isFirstLogin'] = false;
            $data['isFirstLogin'] = false;
        }else{
            $data['isFirstLogin'] = true;
            $_SESSION['UserInfo']['isFirstLogin'] = true;
        }
        $this->ok($data);
        add_oplog('执行','1001',$str_user_name,null,null,0, '登录成功');

        $model->afterLogin($arr_user_info['EID'], $arr_user_info['UserID']);
        RedisDataManager::setEPOffLineTime($arr_user_info['EID'],$arr_user_info['EID'],$arr_user_info['EID']);

        return;
    }

    public function regAction()
    {
        $str_user_unique_name    = $this->param('useridname', '');
        $str_user_type           = intval($this->param('type', 1));
        $str_code                = $this->param('code', '');
        $str_user_pwd            = $this->param('userpwd', '');
        $str_user_r_pwd          = $this->param('userrpwd', '');
        //检测用户
       if(\Tx\Is::email($str_user_unique_name)){
           $str_user_unique_name_type = 1;
       }elseif(Common::checkPhone($str_user_unique_name)){
           //\Lib\Model\Clean::cleanOrNot($str_user_unique_name);
           $str_user_unique_name_type = 2;
       }else{
           $this->notice("用户名必须是邮箱或者手机格式!", 10);
           return;
       }
        $model = new UserModel();
        if( $model->issetUserName($str_user_unique_name,$str_user_unique_name_type) ) {
            $this->notice("用户名称已经被注册，请重新设置。", 10);
            return;
        }
        if( $str_user_pwd !== $str_user_r_pwd ) {
            $this->notice("两次密码不一致。", 12);
            return;
        }
        if( strlen($str_user_pwd)<8 ) {
            $this->notice("密码格式错误，请重新输入。", 11);
            return;
        }
       if ($str_user_unique_name_type == 1) {
           if(strtolower($str_code) !== strtolower(@$_SESSION['captcha_keystring'])) {
               $this->notice("验证码错误！", 13);
               return;
           }
       }
       if ($str_user_unique_name_type == 2) {
           if(!$model->checkPhoneCode($str_user_unique_name, $str_code, 1)){
               $this->notice("验证码错误或者超时！", 13);
               return;
           }
       }

        $i=0;
        do{
            $ini_org_eid = strtoupper(substr(str_replace(".", "", uniqid("", true)), -16));
            $i++;
        }
        while( $i<10  && $model->eidAlreadyIn($ini_org_eid) );
        if($i>=10){
            $this->notice("用户组织信息创建失败！");
            return;
        }

        $str_create_time = date("Y-m-d H:i:s",time());
        $params_org = array(
            'OType'  => $str_user_type ,
            'IsTrial'=> 1,             //1 试用 |2 正式
            'CreateTime'=> $str_create_time,
            'EID' => $ini_org_eid,
        );
        $ini_crate_org_info_id = $model->createUserOrgInfo($params_org);

        $Salt     = Common::saltMd5();
        $str_user_pwd  = Common::passMd5($str_user_pwd, $Salt, $encrypt = 'md5');
        $para_arr = array(
            'EID'        =>strval($ini_org_eid),
            'UserName'   => $str_user_unique_name,
            'EMail'      => $str_user_unique_name_type == 1 ? $str_user_unique_name : '' ,
            'PhoneNO'     => $str_user_unique_name_type == 2 ? $str_user_unique_name : '' ,
            'PWD'        => $str_user_pwd,
            'Salt'       => $Salt,
            'Status'     => 0,
            'UType'      => $str_user_type,
            'Level'      => 1,
            'LockState'  => 1,
            'CreateTime' => $str_create_time,
        );
        if( !$model->createUserInfo($para_arr) ) {
            add_oplog('执行','1003',$str_user_unique_name,null,null,1, '注册失败');
            $this->notice("注册失败请重新注册。", 1);
            return;
        }

        /*
         * $objIoa
         * stdClass Object
        (
            [status] => 1
            [msg] => 注册管理员成功
            [resultCode] => 0000
            [data] => stdClass Object
                (
                    [resultMsg] => 注册管理员成功
                    [responseNo] => aa3908066f594b898bab2d6fc7a6bd0d
                    [platformNo] => rising
                    [resultCode] => 0000
                    [responseSign] => lIO13a4jYm69Sx28+rFg2ajqL3QDB2cZVm+iq81UXFMYn7O7Tci0qo3D/ylZ2k4NcIR1feOafuDz7q4tlt2W4B9pzMY0cS3jsfyB7EmA6fqIsNepuPySpKQz4KRvaZWV/VZbubcBfdAQbldL6k+a7fQ5vXLAyrs2vzMjgeqCBjI=
                    [orgCode] => f354389392b04304b705eddc3bba2561
                    [userId] => af05fe5f8165421c86cd4aa106a869f7
                )

        )
         */
        $objIoa = Ioa::createIoaOrg( $str_user_unique_name);

        if($objIoa->data->resultCode !== '0000' && $objIoa->data->resultCode !== '3003'){
            //Lib\Model\Clean::cleanEidInfo( strval($ini_org_eid) );
            Redis::lPush(REDIS_IOA_ORGID_QUEUE, json_encode(array('eid'=>strval($ini_org_eid),'username'=>$str_user_unique_name)));
            //$this->notice("生成数据失败。", 1);
            //return;
        }else{
            $esm_eid = strval($ini_org_eid);
            $ioa_orgCode = $objIoa->data->orgCode;
            $ioa_userId = $objIoa->data->userId;
            $ioaModel = new IoaOrgidModel();
            $ioa_data = array(
                'eid'     =>  $esm_eid ,
                'orgid'   =>   $ioa_orgCode,
                'userid'  =>   $ioa_userId,
                'disk_set' =>0,
                'add_time' =>  $str_create_time
            );
            RedisDataManager::setIoaorgid($esm_eid,$ioa_orgCode);
            RedisDataManager::setIoaCenterDisk($esm_eid,0);
            if(!$ioaModel->insertOrgid( $ioa_data )){
               // Lib\Model\Clean::cleanEidInfo( $esm_eid );
                $this->notice("关联数据失败。", 1);
                return;
            }
        }

        if ($str_user_unique_name_type == 1) {
            $data = array(
                'for' => 'reg',
                'eid' => $para_arr['EID'],
                'email'=>$para_arr['EMail'],
                'time' => time(),
                'oType'=>$str_user_type
            );
            $token = Crypt::encrypt($data);
            EmailQueue::push([['name'=>$para_arr['EMail'], 'email'=>$para_arr['EMail'],'oType'=>$str_user_type]],
                '瑞星安全云用户注册验证',
                Common::emailRegHtml($token)
            );
        }
        $down = new DownloadModel();
        $down->addEID($para_arr['EID']);
        if ($str_user_unique_name_type == 2) {
            if(!$model->activeUser($para_arr['EID'])){
                Lib\Model\Clean::cleanRubbishInfo( $para_arr['EID'] );
                add_oplog('执行','1003',$str_user_unique_name,null,null,1, '激活失败');
                $this->notice("激活用户失败。", 1);
                return;
            }
            $this->ok(
                array('location'=>array('m'=>'Index', 'a'=>'login','oType'=>$str_user_type,)),
                "欢迎加入瑞星安全云！"
            );
            add_oplog('执行','1003',$str_user_unique_name,null,null,0, '注册成功');
            return;
        }
        $this->ok(
            array('location'=>array('m'=>'Index', 'a'=>'registerDone','email'=>$str_user_unique_name,'oType'=>$str_user_type)),
            "欢迎加入瑞星安全云！"
        );
        Redis::sAdd('getalleid', $ini_org_eid);//redis中EID的存储结构,结构类型：Set； key：getalleid；member：EID
        add_oplog('执行','1003',$str_user_unique_name,null,null,0, '注册成功');
        return;

    }

    public function activeAction()
    {
        $token = $this->param('token', '');
        try{
            $data = Crypt::decrypt($token);
        }catch(\Exception $e){
            $this->assign('content','激活失败。');
            $this->assign('btnTxt','重新注册');
            $this->assign('url','/Index/register');
            $this->assign('email',$data['email']);
            $this->disply('Index/error','.php');
            return;
        }
        if($data['for'] !== 'reg' || time()-$data['time'] > 24*60*60){
            \Lib\Model\Clean::user();
            $this->assign('content','链接失效。');
            $this->assign('btnTxt','重新注册');
            $this->assign('url','/Index/register');
            $this->assign('email',$data['email']);
            $this->disply('Index/error','.php');
            return;
        }
        $model = new UserModel();
        $result = $model->activeUser($data['eid']);
        if(!$result){
            $this->assign('content','激活失败！');
            $this->assign('btnTxt','请重新注册');
            $this->assign('url','/Index/register');
            $this->assign('oType',$data['oType']);
            $this->assign('email',$data['email']);
            $this->disply('Index/error','.php');
            return;
        }
        $this->assign('content','激活成功。');
        $this->assign('btnTxt','立即登录');
        $this->assign('url','/Index/login');
        $this->assign('oType',$data['oType']);
        $this->assign('email',$data['email']);
        $this->disply('Index/activation','.php');
        return;
    }

    public function findbyemailAction(){
        $this->disply('Index/forgotPwdForEmail');
    }

    public function findbyphoneAction(){
        $this->disply('Index/forgotPwdForPhone');
    }

    public function registerdoneAction(){
        $this->disply('Index/registerDone');
    }

    public function registererrorAction(){
        $this->disply('Index/registerError','.php');
    }

    public function resetpwdsucessAction(){
        $this->disply('Index/resetPwdSucess');
    }

    public function resetpwdAction(){
        $this->disply('Index/resetPwd');
    }

    public function getmailAction(){
        $email = $this->param('email', '');
        $model = new UserModel();
        $result = $model->getUserByEmail($email);
        if($result['Status'] == 1){
            $this->notice("账户已经激活", 10);
            return;
        }
        $data = array(
            'for' => 'reg',
            'eid' => $result['EID'],
            'time' => time(),
        );
        $token = Crypt::encrypt($data);
        EmailQueue::push([['name'=>$email, 'email'=>$email,]],
            '用户注册',
            Common::emailRegHtml($token)
        );
        $this->ok(array('location'=>array('m'=>'Index','a'=>'registerdone')), "邮件发送成功。");
        return;
    }

    public function getphonebyuserAction()
    {
        $str_username = isset($_POST['phoneNum']) ? $_POST['phoneNum'] :'';
        \Lib\Model\Clean::cleanOrNot($str_username);
        $model = new UserModel();
        if(!Common::checkPhone($str_username)){
            Common::out(Common::returnAjaxMsg(1, 0, "手机格式不正确。"));
        }

        if( $model->issetUserName($str_username,2) ) {
            Common::out(Common::returnAjaxMsg(1, 0, "用户名已经被注册。"));
        }
        $model = new UserModel();
        $seccuss = $model->smsVerify(1, $str_username);
        if ($seccuss != 0) {
            Common::out(Common::returnAjaxMsg(2, 0, "获取验证码失败"));
        }
        Common::out(Common::returnAjaxMsg(0, 0, "验证码已发送，请查收"));
    }

    public function packageAction()
    {
        $eid = isset($this->_eid) ? $this->_eid : '';
        if(!$eid){
            $this->notice("未登录", 401);
            return;
        }
        $ptf = $this->param('platform', '');
        $urls = require(__DIR__ . '/../../config/urls.php');
        $dlurl = sprintf('%s/index/dl?platform=%s&eid=%s', $urls['platform'], $ptf, $eid);
        $result = (new DownloadModel())->getLanPackage($eid, $ptf);
        if(!is_array($result)){
            $this->notice($result, 1);
            return;
        }
        if($result['error']){
            $this->notice($result['error'], 1);
            return;
        }
        if(!empty($result['result']['base']['link'])){
            $result['result']['base']['qr'] = '/index/qr?url=' . urlencode($dlurl);
        }
        if(!empty($result['result']['dist']['link'])){
            $result['result']['dist']['qr'] = '/index/qr?url=' . urlencode($dlurl);
        }
        $this->ok($result['result']);
    }

    public function qrAction()
    {
        header('Content-type: image/png');
        $qrCode = new QrCode();
        $qrCode
            ->setText($this->param('url', 'lru'))
            ->setSize(70)
            ->setPadding(0)
            ->setErrorCorrection('high')
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            ->render()
            ;
    }

    public function dlAction()
    {
        $eid = $this->param('eid', '');
        $ptf = $this->param('platform', '');
        $result = (new DownloadModel())->getPackage($eid, $ptf);
        if(!is_array($result)){
            $this->notice($result, 1);
            return;
        }
        if($result['error']){
            $this->notice($result['error'], 1);
            return;
        }
        if(!empty($result['result']['dist']['link'])){
            header('Location: '.$result['result']['dist']['link']);
            return;
        }
        if(!empty($result['result']['base']['link'])){
            header('Location: '.$result['result']['base']['link']);
        }
    }

    public function getSignAction()
    {
        $eid = isset($this->_eid) ? $this->_eid : '';
        $ioaModel = new IoaOrgidModel();
        $aEid = $ioaModel->getUseridAndOrgidByEid( $eid );
        $aParams = Ioa::getIoaRsaSign($aEid['orgid'],$aEid['userid']);
        $this->ok($aParams);
    }
}


