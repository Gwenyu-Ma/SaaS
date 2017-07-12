<?php
class UserController extends MyController
{
    public function init()
    {
        parent::init();
        Yaf_Dispatcher::getInstance()->disableView();
    }
    public function testGetUserByIdAction()
    {
        $_POST = array (
                        'eid' => 'XRgg',
                        'userId' => '1'
        );
        $this->getUserByIdAction();
    }

    /**
     * 根据id获取用户信息
     */
    public function getUserByIdAction()
    {
        if (empty($_POST['eid']) || empty($_POST['userId'])) {
            Common::out(Common::returnAjaxMsg(1, 0, "参数错误。"));
        }
        $userModel = new UserModel();
        if (is_null($user = $userModel->getUserByID($_POST['eid'], $_POST['userId']))) {
            Common::out(Common::returnAjaxMsg(2, 0, "用户信息不存在。"));
            return;
        }
        Common::out(Common::returnAjaxMsg(0, 0, "成功。", $user));
    }
    public function testUpdateUserAction()
    {
        $_POST = array (
                        'eid' => 'XRgg',
                        'userId' => '1',
                        'userName' => 'cc',
                        'eMail' => 'eMail',
                        'phoneNo' => '12345678910',
                        'uType' => '0',
                        'level' => '0'
        );
        $this->updateUserAction();
    }

    /**
     * 更新用户信息
     */
    public function updateUserAction()
    {
        if (empty($_POST['eid']) || empty($_POST['userId'])) {
            Common::out(Common::returnAjaxMsg(1, 0, "参数错误。"));
        }
        $userModel=new UserModel();
        if($userModel->updateUser($_POST)){
            Common::out(Common::returnAjaxMsg(0, 0, "更新用户信息成功。"));
            return;
        }
        Common::out(Common::returnAjaxMsg(2, 0, "更新用户信息失败。"));
    }

    public function getusersettingAction()
    {
        $userModel=new UserModel();
        $result=$userModel->getUserSetting($this->_eid,$this->_uid);
        $this->ok($result,'成功');
    }

    public function setusersettingAction()
    {
        $userModel=new UserModel();
        $ok=$userModel->setUserSetting($this->_eid,$this->_uid,$this->param('setStr',''));
        $ok? $this->ok(null,'成功'):$this->notice('修改设置失败',1);
    }
}
