<?php

// 此文件内常量可以直接使用，不用require

define('MONGO_MANAGE_DB', 'rec_manager');
define('MONGO_LOG_DB', 'rec_log');

define('REDIS_EMAIL_QUEUE_KEY', 'email_queue');
define('REDIS_EPINFO_QUEUE', 'epinfo_queue');//客户端队列，接收所有客户端放到此队列后逐条入库
define('REDIS_EPUNSET_QUEUE', 'epunset_queue');//客户端卸载状态队列，客户端卸载信息放到此队列后逐条入库
define('REDIS_DISK_QUEUE', 'disk_queue');//客户端网盘是否开启默认策略 {"eid": "","sguid": "","disk": 0}
define('REDIS_IOA_ORGID_QUEUE','ioa_orgid_queue');//注册中心时，获取IOA的orgid
define('REDIS_IOA_COMPANY_QUEUE','ioa_company_queue');//修改企业名称 调用IOA接口
define('REDIS_IOA_ADMIN_QUEUE','ioa_admin_queue');//修改管理员

//redis缓存前缀
define("CACHE_REDIS_EP_PRE", "hep_");//hash类型，用于存储组信息、客户端策略、版本信息
define("CACHE_REDIS_ORG_PRE", "heid_");//hash类型，用于存储全网策略、组策略等内容
define("CACHE_REDIS_EP_CMD_PRE", "lcmd_");//list类型，客户端命令列表
define("CACHE_REDIS_CMD_PRE", "strcmd_");//string类型，用于存储命令内容
define("CACHE_REDIS_ONLINESTATE_PRE", "heidos_");//hash类型，用于存储企业所有客户端的心跳信息
define("CACHE_REDIS_IOA_PRE","ioa_"); //hash类型，用于存储爱办公信息

//首页统计数据
define("CACHE_REDIS_OSSTAT_PRE", "hosstat:");//hash类型，首页操作系统类型统计数据
define("CACHE_REDIS_XAVCOUNT_PRE", "hxavcount:");//hash类型，首页操作系统类型统计数据

define("REC_LOG_TOPIC", "recLogTopic");//KAFA的topic名称
define("REC_PARTITIONS_COUNT", 12);//KAFA的分区数

define('DL_PFX', 'dl_');
define('DL_RDS_PKG_ANDROID', DL_PFX . 'android');
define('DL_RDS_PKG_LINUX', DL_PFX . 'linux');
define('DL_RDS_PKG_WINDOWS', DL_PFX . 'windows');
define('DL_RDS_EIDS', DL_PFX . 'eids');
define('DL_RDS_TASKS', DL_PFX . 'tasks');
define('DL_API_KEY', 'yiquganchangduan');

define('MONGO_MESSAGE_DB', 'message');

//redis超时时间
define('REDIS_CMD_OUTTIME',1800);
