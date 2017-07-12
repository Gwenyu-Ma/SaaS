<?php
class Bootstrap extends Yaf_Bootstrap_Abstract{
    private $_config;

    public function _initBootstrap(){
        $this->_config = Yaf_Application::app()->getConfig();
        session_start();
        Yaf_Loader::import(APP_PATH."conf/constant.php");
    }

    public function _initIncludePath(){
        set_include_path(get_include_path().PATH_SEPARATOR.$this->_config->application->library);
    }

    public function _initRoutes(){
        require_once APP_PATH."conf/config.php";
        $rout_yaf = Yaf_Dispatcher::getInstance()->getRouter();
        foreach ($config['URL_REDIRECT'] as $value){
            $route = new Yaf_Route_Regex($value['1']['0'],$value['1']['1']);
            $rout_yaf->addRoute($value['0'],$route);
        }
    }

    public function _initSmarty(Yaf_Dispatcher $dispatcher) {
        $smarty = new SmartyAdapter(null, require(APP_PATH .'/conf/smarty.php'));
        Yaf_Dispatcher::getInstance()->setView($smarty);
    }

    public function _initNamespaces(){
        Yaf_Loader::getInstance()->registerLocalNameSpace(array("Zend"));
    }

    public function _initDefaultDbAdapter(){
        $dbs = require(__DIR__ . '/../config/mysql.php');
        $one = $dbs['write'][0];
        $dbAdapter = new Zend_Db_Adapter_Pdo_Mysql($one);
        $dbAdapter->query("set names 'utf8'");
        Zend_Db_Table::setDefaultAdapter($dbAdapter);
        $GLOBALS['DB'] = $dbAdapter;
    }

}
