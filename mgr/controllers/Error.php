<?php

/**
 * Created by IntelliJ IDEA.
 * User: guodf
 * Date: 16-8-30
 * Time: 下午10:55
 */
class ErrorController extends Yaf_Controller_Abstract
{
    public function init()
    {
        Yaf_Dispatcher::getInstance()->disableView();
    }
    public function errorAction()
    {
        echo $this->_request->getException()->getMessage();
    }
}