<?php
//ini_set("memory_limit","200M");
class MyController extends Yaf_Controller_Abstract
{
    public $_global_config = array();
    protected $_form_config = array();
    protected $userinfo;
    protected $request;
    public function init()
    {
        Yaf_Dispatcher::getInstance()->disableView();
        $this->request = Yaf_Dispatcher::getInstance()->getRequest();

        if (!empty($_SESSION['UserInfo'])) {
            $this->userinfo = $_SESSION['UserInfo'];
            $this->_eid = $this->userinfo['EID'];
            $this->_uid = $this->userinfo['UserID'];
            $this->_userName=$this->userinfo['UserName'];
            //var_dump($this->userinfo);
            return;
        }
        if (!in_array($this->request->getControllerName(), ['Iphone','Index', 'Error', 'Forgotpwd'])) {
            if ($this->request->isXmlHttpRequest()) {
                $this->notice("未登录", 401);
            } else {
                header('Location: /login.html');
            }
            exit;
        }
    }

    public function param($name, $default = null)
    {
        $params = $this->request->getRequest();
        if (!isset($params[$name])) {
            return $default;
        }
        return $params[$name];
    }
    public function params()
    {
        return $this->request->getRequest();
    }

    // ok, here your data
    public function ok($data, $message = '')
    {
        $this->response(0, 0, $message, $data);
    }
    // notice
    public function notice($msg, $code = 0)
    {
        $this->response($code, 0, $msg, null);
    }
    // :(
    public function alert($msg, $code = 0)
    {
        $this->response($code, 1, $msg, null);
    }
    // 参数说明：状态码(0:成功,1：失败,2：超时），客户端行为(0:提示,1:弹窗),返回客户端弹窗内容，响应数据
    public function response($code, $action, $msg, $data)
    {
        $result = array(
            "r" => array(
                "code" => $code,
                "action" => $action,
                "msg" => $msg,
            ),
            'data' => $data,
        );
        echo json_encode($result);
    }

    //获取当前控制器下表单配置信息
    public function includeFormConfig()
    {
        Yaf_Loader::import(APP_PATH . "conf/form.php");
        $current_controller = $this->getRequest()->getControllerName();
        $this->_form_config = $form[strtolower($current_controller)];
    }

    //获取当前控制器下表单配置信息
    public function includeGlobalConfig()
    {
        require APP_PATH . "conf/config.php";
        $this->_global_config = $config;
    }

    public function assign($tpl_var, $val = null)
    {
        $this->_view->assign($tpl_var, $val);
    }

    public function disply($html, $suffix = '.html', $message = '')
    {
        $URL = '/';
        $this->_view->assign("pub", $URL . "public/");
        $this->_view->assign("js", $URL . "public/js");
        $this->_view->assign("css", $URL . "public/css");
        $this->_view->assign("img", $URL . "public/img");
        //var_dump($this->userinfo['NickName']);
        $this->_view->assign('user', json_encode($this->userinfo));
        //$str = '/@rising.com.cn$/';
        //$showNet = preg_match($str,$this->userinfo['UserName']);
        $showNet = true;
        $this->_view->assign('showNet',$showNet);
        echo $this->_view->render($html . $suffix, $message);
    }
}
