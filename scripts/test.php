<?php
require_once(__DIR__ . '/../vendor/autoload.php');
use Lib\Store\RedisCluster as Redis;
use Lib\Util\Email;
use Lib\Store\MysqlCluster as MC;
use \Lib\Model\AutoGroup;
use Lib\Store\Mysql as DB;

if($argc > 1)$argv[1]();

function clear_dl_redis(){
    $r = Redis::keys('dl_*');
    foreach($r as $v){
        Redis::del($v);
    }
}

function email(){
    try{
        $ok = Email::send([['name'=>'Cloud', 'email'=>'bot@ym.txthinking.com']], 'subject', 'body');
        var_dump($ok);
    }catch(\Exception $e){
        var_dump($e);
    }
}

function file_get_content($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
    curl_setopt($ch, CURLOPT_URL, $url);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
};
function sign(){
    error_reporting(~0);
    ini_set('display_errors', 1);
    $body = \Unirest\Request\Body::multipart([], ['name'=>'/home/tx/downloader3.exe']);
    $r = \Unirest\Request::post('http://193.168.10.117:8089/sign', [], $body);
    if($r->code !== 200){
        echo $r->code . "\n";
        return;
    }
    echo $r->raw_body . "\n";
    $ok = file_put_contents('/tmp/fuck.exe', file_get_content($r->raw_body));
    var_dump($ok);
}

function rfwlog(){
    $eid = '49024C4475220410';
    MC::$eid = $eid;
    $tb = "RFW_BrowsingAuditLog_$eid";
    for($i=0;$i<60;$i++){
        MC::exec("insert into $tb values(null,'2016-01-01 01:01:$i','$eid','$i','$i','2016-01-01 01:01:$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i')");
    }
    $tb = "RFW_IPAccessAuditLog_$eid";
    for($i=0;$i<60;$i++){
        MC::exec("insert into $tb values(null,'2016-01-01 01:01:$i','$eid','$i','$i','2016-01-01 01:01:$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i')");
    }
    $tb = "RFW_NetProcAuditLog_$eid";
    for($i=0;$i<60;$i++){
        MC::exec("insert into $tb values(null,'2016-01-01 01:01:$i','$eid','$i','$i','2016-01-01 01:01:$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i')");
    }
    $tb = "RFW_SharedResAccessAuditLog_$eid";
    for($i=0;$i<60;$i++){
        MC::exec("insert into $tb values(null,'2016-01-01 01:01:$i','$eid','$i','$i','2016-01-01 01:01:$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i')");
    }
    $tb = "RFW_SharedResListLog_$eid";
    for($i=0;$i<60;$i++){
        MC::exec("insert into $tb values(null,'2016-01-01 01:01:$i','$eid','$i','$i','2016-01-01 01:01:$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i')");
    }
    $tb = "RFW_TerminalFlowAuditLog_$eid";
    for($i=0;$i<60;$i++){
        MC::exec("insert into $tb values(null,'2016-01-01 01:01:$i','$eid','$i','$i','2016-01-01 01:01:$i','$i','$i','$i','$i','$i','$i','2016-01-01 01:01:$i','2016-01-01 01:01:$i','$i','$i','$i','$i')");
    }
    $tb = "RFW_UrlInterceptLog_$eid";
    for($i=0;$i<60;$i++){
        MC::exec("insert into $tb values(null,'2016-01-01 01:01:$i','$eid','$i','$i','2016-01-01 01:01:$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i')");
    }
}

function autogroup(){
    $ag = new AutoGroup();
    $r = $ag->autoInGroup('F118BB3899203562', '221F025C09EC27DB804B3739D6466E41');
    var_dump($r);
}

function fuckmysql(){
    $r = DB::getAll('select * from (select test.*,test1.fuck  from test inner join test1 on test.OID=test1.OID) as x;');
    print_r($r);
}

function cmdlog(){
    $eid = '9502DE2628723333';
    MC::$eid = $eid;
    for($i=1;$i<60;$i++){
        MC::exec("insert into CMDInfo_$eid values('$i', '$eid', '$i', '$i', '$i', '$i', '$i', '$i', '$i', '2011-11-11 01:01:$i')");
        for($j=1;$j<6;$j++){
            MC::exec("insert into CMDIssuedState_$eid values(null, '$i', '$eid', '$j', '$j', '$j', '$j', '2011-11-11 01:01:$j', '2011-11-11 01:01:$j')");
        }
    }
}

function emailjson(){
    $data = array(
        'tos' => array(array('name'=>'TOM', 'email'=>'TOM@GMAIL.COM')),
        'subject' => 'SUBJECT',
        'body' => 'BODY',
        'attachments' => [],
    );
    echo json_encode($data);
}
