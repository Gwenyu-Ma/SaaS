<?php
use \Lib\Store\MongoClient;
use ChromePhp as Console;

function select_manage_collection($collection)
{
    return MongoClient::selectDB(MONGO_MANAGE_DB)->selectCollection($collection);
}

function select_log_collection($collection)
{
    return MongoClient::selectDB(MONGO_LOG_DB)->selectCollection($collection);
}

function d()
{
    echo '<pre>';
    call_user_func_array('var_dump', func_get_args());
    exit;
}

function json($var)
{
    return json_encode($var, JSON_UNESCAPED_UNICODE);
}

function unjson($var, $assoc = false)
{
    return json_decode($var, $assoc);
}

function add_oplog($action, $funcs,$objects,$source,$target,$result,$description=null)
{
    $eid = "";
    $username = "";
    $ip = $_SERVER['REMOTE_ADDR'];

    if(!empty($_SESSION['UserInfo'])){
        $eid = $_SESSION['UserInfo']['EID'];
        $username = $_SESSION['UserInfo']['UserName'];
    }else{
        $log = select_manage_collection('manage_log');
        $log->insert([
            'eid' => 'unknown',
            'username'=>'unknown',
            'resource'=>$objects,
            'action'=>'login',
            'desc'=>'login error',
            'time'=>time(),
        ]);
        return;
        //throw new \Exception("need eid and username, so you must login first");
    }
    if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    $memo=$result;
    $result=!empty($result)&&strcasecmp('成功',$result)!=0? 1:0;
    \Lib\Model\Oplog::add($eid, $username, $ip,$action, $funcs,$objects,$source,$target,$result,$memo,$description);
}


