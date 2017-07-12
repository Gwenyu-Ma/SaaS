<?php

/**
 * Created by IntelliJ IDEA.
 * User: guodf
 * Date: 16-8-28
 * Time: 下午11:12
 */
class Bootstrap extends Yaf_Bootstrap_Abstract
{
    private $_config;

    public function _initBootstrap(){
        $this->_config = Yaf_Application::app()->getConfig();
        session_start();
    }

    public function _initIncludePath(){
        set_include_path(get_include_path().PATH_SEPARATOR.$this->_config->application->library);
    }

    public function _initSmarty(Yaf_Dispatcher $dispatcher) {
        $smarty = new SmartyAdapter(null, require(APP_PATH .'/conf/smarty.php'));
        Yaf_Dispatcher::getInstance()->setView($smarty);
    }

    public function _initNamespaces(){
        Yaf_Loader::getInstance()->registerLocalNameSpace(array("Zend"));
    }

    public function _initDefaultDbAdapter(){
        $dbs = require(__DIR__ . '/../config/mgr_mysql.php');
        $one = $dbs['write'][0];
        $dbAdapter = new Zend_Db_Adapter_Pdo_Mysql($one);
        $dbAdapter->query("set names 'utf8'");
        Zend_Db_Table::setDefaultAdapter($dbAdapter);
        $GLOBALS['DB'] = $dbAdapter;
    }
}