<?php
/*自动加载IOA的sdk*/
include dirname(__FILE__)."/IoaSdk.class.php";

/*IoaSdk初始化操作*/
$ioaSdk = new IoaSdk();
$ioaSdk->init();
$ioaSdk->setDebugState(false);

/*数据格式输出支持entity,json*/
$ioaSdk->setTransferDataType("json");