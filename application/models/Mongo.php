<?php
class MongoModel
{
	static $read_manage_mongo;
	static $write_manage_mongo;
	static $read_log_mongo;
	static $write_log_mongo;

	public function __get($dbtype){
       $rc4 = new rc4();
       $mongodb_info = json_decode($rc4->decrypt(Common::cookie('MongoDBInfo')), true);
       $result = false;

    	if (!isset($dbtype) || !in_array($dbtype, array('read_manage_mongo', 'write_manage_mongo', 'read_log_mongo', 'write_log_mongo'))) {
    		return $result;
    	}

       if (!empty($mongodb_info)) {
           if (in_array(intval($mongodb_info['cnt']), array(1, 2, 4))) {
           	$dbname_arr = explode('_', $dbtype);
           	$dbname = $mongodb_info[$dbname_arr[1].'_db'];

           	switch (intval($mongodb_info['cnt'])) {
           		case 1:
           			$host = $mongodb_info['host']['host'];
           			break;
           		case 2:
           			$host = $mongodb_info['host'][$dbname_arr[1].'_host'];
           			break;
           		case 4:
           			$host = $mongodb_info['host'][$dbname_arr[0].'_'.$dbname_arr[1].'_host'];
           			break;
           		default:
           			# code...
           			break;
           	}
           }

           $mongo = new MongoProcess(array('host' => $host));
           $result = array('mongo' => $mongo, 'dbname' => $dbname);
           return $result;
       } else {
           return Common::out(Common::returnAjaxMsg(2, 0, "用户登录信息已过期", ""));
       }
	}
}

/*$t = new mongoConnect();
$_SESSION['UserInfo']['CurrentMongodbInfo'] = $t;
print_r($_SESSION['UserInfo']['CurrentMongodbInfo']->read_manage_mongo); */