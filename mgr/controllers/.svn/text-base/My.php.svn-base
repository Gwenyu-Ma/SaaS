<?php

class MyController extends Yaf_Controller_Abstract
{
    public function init()
    {
        Yaf_Dispatcher::getInstance()->disableView();
        $this->request = Yaf_Dispatcher::getInstance()->getRequest();

        if (!in_array($this->request->getControllerName(), ['Index', 'Error'])) {
             if(!$_SESSION['manager']){
                header('Location: /');
            }
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

    public function assign($tpl_var, $val = null)
    {
        $this->_view->assign($tpl_var, $val);
    }

    public function disply($html, $suffix = '.html', $message = '')
    {
        $this->_view->assign('pub',  '/mgr/public');
        echo $this->_view->render($html . $suffix, $message);
    }

}