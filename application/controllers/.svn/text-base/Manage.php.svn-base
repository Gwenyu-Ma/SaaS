<?php
/**
 * Created by PhpStorm.
 * User: 崔京哲
 * Date: 2015/9/4
 * Time: 15:26
 */
class ManageController extends MyController
{
    private $_rc4;
    public function init()
    {
        parent::init();
        Yaf_Dispatcher::getInstance()->disableView();
//        parent::includeFormConfig();
        //        $current_controller = $this->getRequest()->getControllerName();
        //        $current_action = $this->getRequest()->getActionName();
        //        $action_arr = array(
        //
        //        );
        //
        //        if (!in_array($current_action, $action_arr) && !parent::init()) {
        //            exit;
        //        }
        $this->_rc4 = new rc4();

    }

    public function indexAction()
    {
        $globalGroupID = $_SESSION['GroupInfo']['GlobalGroupID'];
        $this->assign('globalGroupID', $globalGroupID);
        $this->disply("Manage/index2", '.php'); //注册老用户页面

    }

    public function virusTraceAction()
    {
        $this->display('VirusTrace');
    }
}
