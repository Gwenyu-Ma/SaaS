<?php
use \Lib\Util\Crypt;
use \Lib\Util\EmailQueue;

class ForgotpwdController extends MyController
{
    public function init()
    {
        parent::init();
        Yaf_Dispatcher::getInstance()->disableView();
    }

    /**
     * 验证点击邮件中的链接参数
     */
    public function verifyMailLinkAction()
    {
        $token = $this->param('token', '');
        try{
            $data = Crypt::decrypt($token);
        }catch(\Exception $e){
            echo "该链接已经过期!";
            header('refresh: 2; url=/findPwdByEmail.html');
            return;
        }
        if($data['for'] !== 'forgot' || time()-$data['time'] > 24*60*60){
            echo "该链接已经过期!";
            header('refresh: 2; url=/findPwdByEmail.html');
            return;
        }
        setcookie('token', $token, time()+7*24*60*60, '/');
        $this->assign('userName',empty($data['userName'])? '':$data['userName']);
        $this->assign('oType',empty($data['oType'])? '':$data['oType']);
        $this->disply("Index/resetPwd");
    }

    /**
     * 重置密码
     */
    public function resetPwdAction()
    {
        $token = @$_COOKIE['token'];
        try{
            $data = Crypt::decrypt($token);
        }catch(\Exception $e){
            $this->notice("非法请求", 1);
            return;
        }
        if($data['for'] !== 'forgot' || time()-$data['time'] > 24*60*60){
            $this->notice("非法请求", 1);
            return;
        }

        $pwd1 = $this->param("pwd1", '');
        $pwd2 = $this->param("pwd2", '');
        if ($pwd1 == '') {
            $this->notice("密码不可为空", 1);
            return;
        }
        if ($pwd1 != $pwd2) {
            $this->notice("密码不一致", 1);
            return;
        }
        $userModel = new UserModel();
        $userModel->resetPwd($data['uid'], $pwd1);
        $this->ok(array ('location' => array (
                'm' => 'Index',
                'a' => 'resetpwdsucess'
            )), "密码修改成功");
        setcookie('token', '', time()+7*24*60*60, '/');
        return;
    }

    /**
     * 验证邮箱
     */
    public function validEmailAction()
    {
        $email = isset($_POST["email"]) ? $_POST["email"] : null;
        if (is_null($email) || empty($email)) {
            Common::out(Common::returnAjaxMsg(1, 0, '请输入邮箱地址'));
        }
        $userModel = new UserModel();
        if (!Common::checkEmail($email)) {
            Common::out(Common::returnAjaxMsg(2, 0, "[$email]邮箱格式不正确"));
        }
        $userInfo = $userModel->getUserInfoByEmail($email);
        if (empty($userInfo) || is_null($userInfo)) {
            Common::out(Common::returnAjaxMsg(3, 0, '此账号未注册'));
        }
        if ($userInfo['Status'] == '0') {
            Common::out(Common::returnAjaxMsg(4, 0, '此账号未激活'));
        }
        Common::out(Common::returnAjaxMsg(0, 0, '邮箱有效'));
    }

    /**
     * 发送找回密码邮件
     */
    public function retrievePwdByEmailAction()
    {
        $email = $this->param('email', '');
        $code = $this->param('checkCode', '');
        $model = new UserModel();
        $user = $model->getUserByEmail($email);
        if(!$user){
            $this->notice('账户不存在', 1);
            return;
        }
        if($user['Status'] != 1){
            $this->notice('账户未激活', 4);
            return;
        }
        if(strtolower($code) !== strtolower(@$_SESSION['captcha_keystring'])) {
            $this->notice("验证码错误！", 3);
            return;
        }
        $data = array(
            'for' => 'forgot',
            'uid' => $user['UserID'],
            'time' => time(),
            'userName'=>$user['UserName'],
            'oType'=>$user['OType']
        );
        $token = Crypt::encrypt($data);
        EmailQueue::push([['name'=>$email, 'email'=>$email,]],
            '找回密码',
            Common::emailForgotPwdHtml($token)
        );
        $this->ok(null, '请登录邮箱查收邮件并完成剩余操作');
        return;
    }

    /**
     * 验证手机号
     */
    public function validPhoneAction()
    {
        $phone = isset($_POST["phone"]) ? $_POST['phone'] : null;
        if (empty($phone) || is_null($phone)) {
            Common::out(Common::returnAjaxMsg(1, 0, '请输入手机号'));
        }

        if (!Common::checkPhone($phone)) {
            Common::out(Common::returnAjaxMsg(2, 0, "[$phone]手机号格式错误"));
        }
        $userModel = new UserModel();
        $userInfo = $userModel->getUserInfoByPhone($phone);
        // var_dump($userInfo);
        if (empty($userInfo) || is_null($userInfo)) {
            Common::out(Common::returnAjaxMsg(3, 0, '此手机号未注册'));
        }
        if ($userInfo['Status'] == '0') {
            Common::out(Common::returnAjaxMsg(4, 0, '此账号未激活'));
        }
        $_SESSION["user"] = $userInfo;
        Common::out(Common::returnAjaxMsg(0, 0, "手机号有效"));
    }

    /**
     * *
     * 获取手机验证码
     */
    public function getPhoneCodeAction()
    {
        $userInfo = isset($_SESSION["user"]) ? $_SESSION['user'] : null;
        // var_dump($userInfo);
        if (!(is_array($userInfo) && !is_null($userInfo))) {
            Common::out(Common::returnAjaxMsg(1, 0, '超时,请重新验证手机号'));
        }
        $userModel = new UserModel();
        $seccuss = $userModel->smsVerify(2, $userInfo["PhoneNO"]);
        // var_dump($seccuss);
        if ($seccuss != 0) {
            Common::out(Common::returnAjaxMsg(2, 0, "获取验证码失败"));
        }
        Common::out(Common::returnAjaxMsg(0, 0, "验证码已发送，请查收"));
    }

    /**
     * *
     * 根据手机找回密码，验证验证码
     */
    public function resetPwdByPhoneAction()
    {
        $checkCode = isset($_POST['checkCode']) ? $_POST['checkCode'] : '';
        $userInfo = isset($_SESSION["user"]) ? $_SESSION['user'] : null;

        if (!(is_array($userInfo) && !is_null($userInfo))) {
            Common::out(Common::returnAjaxMsg(1, 0, '超时,请重新验证'));
        }

        // 验证验证码
        if (empty($checkCode) || is_null($checkCode)) {
            Common::out(Common::returnAjaxMsg(2, 0, "请输入验证码"));
        }
        $phoneModel = new PhoneMsgModel();
        $userID = $userInfo["UserID"];

        $phoneMsg = $phoneModel->getPhoneMsg($userInfo["PhoneNO"], 2);
        if (is_null($phoneMsg) || empty($phoneMsg["CheckCode"]) || is_null($phoneMsg["CheckCode"]) || strcasecmp($phoneMsg["CheckCode"], $checkCode) != 0) {
            Common::out(Common::returnAjaxMsg(3, 0, "验证码错误"));
        }
        if ((time() - EXPERIOD_CODE_TIME) > strtotime($phoneMsg["SendTime"])) {
            Common::out(Common::returnAjaxMsg(4, 0, "验证码过期"));
        }
        $data = array(
            'for' => 'forgot',
            'uid' => $userID,
            'time' => time(),
        );
        $token = Crypt::encrypt($data);
        setcookie('token', $token, time()+7*24*60*60, '/');
        $this->ok(array (
            'location' => array (
                'm' => 'Index',
                'a' => 'resetpwd',
                'userName'=>$userInfo['UserName'],
                'oType'=>$userInfo['OType']
            )
        ));
    }

    public function assign($tpl_var, $val = null)
    {
        $this->_view->assign($tpl_var, $val);
    }
}
