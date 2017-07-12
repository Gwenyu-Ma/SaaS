<?php
define('APP_PATH',dirname(dirname(__FILE__)));
define('DS',DIRECTORY_SEPARATOR);
define('API_PATH',APP_PATH.DS);
define('API_FILE_EXT','.class.php');

//sugid生成缓存服务器
define("SGUID_REDIS_HOST", "127.0.0.1");
define("SGUID_REDIS_PORT", "6379");
//下发客户端内容缓存服务器
define("CACHE_REDIS_HOST", "127.0.0.1");
define("CACHE_REDIS_PORT", "6379");

define("MC_HOST", "127.0.0.1");
define("MC_PORT", "11211");
define("MONGODB_HOST", "127.0.0.1");
define("MONGODB_PORT", "27017");
define("MYSQL_HOST", "127.0.0.1");

define("MYSQL_DBNAME", "rs_esm_soho");

define("MYSQL_USER", "root");
define("MYSQL_PWD", "rising");

define("CACHE_REDIS_DB", 1);
define("SGUID_REDIS_DB", 8);
define("CMD_REDIS_DB", 8);
define("POLICY_REDIS_DB", 1);
define("GROUP_REDIS_DB", 1);
//客户端心跳间隔单位毫秒
define("tespan", 60000);
define("PSTAMP", 20150601060606);
define("CSTAMP", 20150601060606);
//上报数据存储在mongo中的文档集后缀名称
define("LOG_DB_SUFFIX", "");
//通讯协议版本
define("protocol", "1.0");
//命令有效时间，单位秒
define("CMDTTL", "43200");

define("API_I_HEAD_ERROR", 1); //Header头未传递I及V信息
define("API_I_HEADNULL_ERROR", 2);//Header头传递I信息为空
define("API_I_EID_ERROR", 3);//Header头信息eid参数缺失
define("API_I_GUID_ERROR", 4);//Header头信息guid参数缺失
define("API_BODY_ERROR", 5);//HTTP内容信息格式错误
define("API_I_HEADER_ERROR", 6);//Header头信息参数错误


