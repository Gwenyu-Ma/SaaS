<?php
//if(strpos($_SERVER['REQUEST_URI'],'.aspx')!==false)
//{
//    echo 'error';
//    return;
//}
require(__DIR__ . '/vendor/autoload.php');

error_reporting(E_ERROR);
define("DS", '/');
define("APP_PATH", dirname(__FILE__).DS.'mgr'.DS);
$app = new Yaf_Application(APP_PATH."conf/application.ini");
$app->bootstrap()->run();