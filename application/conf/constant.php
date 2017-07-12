<?php
/**
 * Created by PhpStorm.
 * User: 崔京哲
 * Date: 2015/9/2
 * Time: 18:08
 */
//=================================================================================
define('CURRENT_SERVER_IP',      $_SERVER['HTTP_HOST']);
//sugid生成缓存服务器
define('SGUID_REDIS_HOST',      '127.0.0.1');
define('SGUID_REDIS_PORT',      6379);
//下发客户端内容缓存服务器
define('CACHE_REDIS_HOST',      '127.0.0.1');
define('CACHE_REDIS_PORT',      6379);


define('EMAIL_REDIS_HOST',      '127.0.0.1');
define('EMAIL_REDIS_PORT',      6379);
define('EMAIL_QUEUE_NAME',      'global_queue_remailasync');


define("CACHE_REDIS_DB",        1);
define('SGUID_REDIS_DB',        1);
define('CMD_REDIS_DB',          1);
define('POLICY_REDIS_DB',       1);
define('GROUP_REDIS_DB',        1);
define("EMAIL_REDIS_DB",        2);

define('REDIS_FIELD_EID_USERNAME',      "username");
define('REDIS_FIELD_EID_TYPE',          "type");
define('REDIS_FIELD_EID_MONGODBHOST',   "mongodbhost");
define('REDIS_FIELD_EID_REGTIME',       "regtime");
//=================================================================================
/* Cookie设置 */
define('COOKIE_EXPIRE'         ,  0);       // Cookie有效期
define('COOKIE_DOMAIN'         ,  '');      // Cookie有效域名
define('COOKIE_PATH'           ,  '/');     // Cookie路径
define('COOKIE_PREFIX'         ,  '');      // Cookie前缀 避免冲突
define('COOKIE_SECURE'         ,  false);   // Cookie安全传输
define('COOKIE_HTTPONLY'       ,  '');      // Cookie httponly设置

//=================================================================================
//mysql表名称
define('MYSQL_DATABASE_SOHO',       "rs_esm_soho");
define('MYSQL_TABLE_USER',       "esm_user");
define('MYSQL_TABLE_USERACTIVATION', "esm_useractivation");
//=================================================================================
define('DEFAULT_TITLE',             "瑞星安全云");

//瑞星账号系统接口url
//define('RISING_USER_INTERFACE_URL', 'http://192.168.20.90:8019/RsAccountAPI/RsWebInterfaceService.asmx?WSDL');
define('RISING_USER_INTERFACE_URL', 'http://rscloud.rising.com.cn/Rsaccountapi/RsWebInterfaceService.asmx?WSDL');
define('RISING_USER_INTERFACE_FUN', 'RSWebInterface');
define('EMAIL_URL_EXPERIOD_TIME',    5*24*3600);//邮件正文链接有效期
define('EXPERIOD_CODE_TIME',         3*60);//验证码有效期

//===================================================
define('EID_KEY_8','To4ySf8q'); //Eid key
define('STR_PAD_LEN',8);
define('ERROR_PWD_TIMES',3); //用户登录密码错误次数
define('USER_COOKIE_TIME', 900);

//mongo数据库 表==================================================
define('MONGO_DB_ESM_MANAGE'  , 'rs_esm_manage_');
define('MONGO_DB_ESM_LOG' , 'rs_esm_log_');
define('MONGO_DB_EID_MAX_NUM' , 200);

define('REDIS_CMD_OUT_TIME',    24*3600);//redis命令过期时间

define('ERROR_PED_TIMES',3);

