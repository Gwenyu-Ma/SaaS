<?php
use \Lib\Store\MongoClient;
use \Lib\Model\AutoGroup;
use \Lib\Util\Log;
use \Lib\Model\RedisDataManager;
use \Lib\Store\RedisCluster as rds;
use \Lib\Util\Common;

use \Lib\Util\Ioa;

/**
 * 出错处理
 */
function  raiseError($errorArr)
{
    echo json_encode($errorArr, JSON_UNESCAPED_UNICODE);
    exit;
}

//function chkEid($eid)
//{
//	$sguidredis = new Redis();
//	$sguidredis->connect(SGUID_REDIS_HOST, SGUID_REDIS_PORT);
//	$sguidredis->select(SGUID_REDIS_DB);
//
//	$r = false;
//	if (isset($eid) && $eid != "") {
//		$r = $sguidredis->hExists($eid, 'regtime');
//	}
//	return $r;
//}

//策略接受情况
//function policySendNum($mongo, $params, $pstamp, $cstamp)
//{
//	$eid = $params['eid'];
//	$sguid = $params['sguid'];
//	$guid = $params['guid'];
//	$collection = $mongo->selectDB('db' . $eid)->selectCollection('computerinfo');
//	$collection->update(array('sguid' => $sguid), array('$set' => array('pstamp' => floatval($pstamp), 'cstamp' => floatval($cstamp))));
//}

//object=>array
function object_array($array)
{
    if (is_object($array)) {
        $array = (array)$array;
    }
    if (is_array($array)) {
        foreach ($array as $key => $value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}

/**
 *  上报日志
 *  dwith 0|1|2 追加型|覆盖型|更新追加型
 */
function reportLog($mongo, $params, $info)
{
    $eid = $params['eid'];
    $sguid = $params['sguid'];
    $guid = $params['guid'];
    $rip = $params['rip'];
    $edate = $params['edate'];
    $info = object_array($info);
    for ($i = 0; $i < count($info); $i++) {
        $collection = $mongo->selectDB('db' . $eid)->selectCollection($info[$i]['logtype'] . LOG_DB_SUFFIX);

        if ($info[$i]['dwith'] == "0") {
            for ($j = 0; $j < count($info[$i]['data']); $j++) {
                $info[$i]['data'][$j]['sguid'] = $sguid;
                $info[$i]['data'][$j]['guid'] = $guid;
                $info[$i]['data'][$j]['rip'] = $rip;
                $info[$i]['data'][$j]['edate'] = $edate;

                $info[$i]['data'][$j]['time'] = new MongoDate(strtotime($info[$i]['data'][$j]['time']));
            }
            $collection->batchInsert($info[$i]['data']);
        } else if ($info[$i]['dwith'] == "1") {
            $collection->remove(array('sguid' => $sguid));
            for ($j = 0; $j < count($info[$i]['data']); $j++) {
                $info[$i]['data'][$j]['sguid'] = $sguid;
                $info[$i]['data'][$j]['guid'] = $guid;
                $info[$i]['data'][$j]['rip'] = $rip;
                $info[$i]['data'][$j]['edate'] = $edate;
                $info[$i]['data'][$j]['time'] = new MongoDate(strtotime($info[$i]['data'][$j]['time']));
            }
            $collection->batchInsert($info[$i]['data']);
        } else if ($info[$i]['dwith'] == "2") {
            for ($j = 0; $j < count($info[$i]['data']); $j++) {
                $info[$i]['data'][$j]['sguid'] = $sguid;
                $info[$i]['data'][$j]['guid'] = $guid;
                $info[$i]['data'][$j]['rip'] = $rip;
                $info[$i]['data'][$j]['edate'] = $edate;
                $info[$i]['data'][$j]['time'] = new MongoDate(strtotime($info[$i]['data'][$j]['time']));

                if ($info[$i]['logtype'] == 'XAV_ScanEvent') {
                    if (isset($info[$i]['data'][$j]['taskid'])) {
                        $where = array('taskid' => intval($info[$i]['data'][$j]['taskid']), 'sguid' => $sguid);
                        $collection->update($where, $info[$i]['data'][$j], array('upsert' => true));
                    }
                } else {
                    if (isset($info[$i]['data'][$j]['uniquevalue'])) {
                        $where = array('uniquevalue' => $info[$i]['data'][$j]['uniquevalue'], 'sguid' => $sguid);
                        $collection->update($where, $info[$i]['data'][$j], array('upsert' => true));
                    }
                }
            }
        }
    }
}

/**
 * 上报计算机信息
 * @param $mongo
 * @param $params
 * @param $info
 */
function computerInfo($params, $info)
{
    $eid = $params['eid'];
    $sguid = $params['sguid'];
    $guid = $params['guid'];
    $systype = $params['systype'];
    $rip = $params['rip'];
    $edate = $params['edate'];
    $createtime = $params['createtime'];
    $clct = select_manage_collection('epinfo');

    $info['computerinfo']['eid'] = $eid;
    $info['computerinfo']['sguid'] = $sguid;
    $info['computerinfo']['guid'] = $guid;
    $info['computerinfo']['systype'] = $systype;
    $info['computerinfo']['onlinestate'] = 1;
    $info['computerinfo']['unset'] = 0;
    $info['computerinfo']['rip'] = $rip;
    $info['computerinfo']['edate'] = $edate;

    if(!empty($info['moduleinfo']))
    {
        $info['computerinfo']['productinfo'] = $info['moduleinfo'];
    }

    try{
        //客户端首次上传，默认600秒登录过期
        if (empty(rds::hGet(CACHE_REDIS_EP_PRE . $eid . $sguid, 'ep_info'))) {
            $edate =  time()+600;
            //更新客户端在线状态
            //当前时间+心跳周期=过期时间
            rds::hSet(CACHE_REDIS_ONLINESTATE_PRE . $eid,
                $systype . ':' . $sguid, $edate);
        }
    } catch (\Exception $e) {
        Log::add("Error",array('ErrorMessage'=>$e->getMessage()));
    }

    $result = $clct->update(array('eid' => $eid, 'sguid' => $sguid), array('$set' => $info['computerinfo']), array('upsert' => true));

    if (!$result['updatedExisting']) {//判断是否是新增客户端

        $info['computerinfo']['createtime'] = $createtime;
        CreateEPTasks(
            $eid,
            $sguid,
            $createtime,
            $info['computerinfo']['computername'],
            $info['computerinfo']['ip'],
            $info['computerinfo']['mac'],
            $info['computerinfo']['os']);
    }

    //更新epinfo
    RedisDataManager::updateEPInfo($eid,$sguid, $info['computerinfo']);
}

/**
 * @param $eid
 * @param $sguid
 * @param $computername
 * @param $ip
 * @param $mac
 * @param $os
 * 客户端第一次加入中心产生的任务集合
 */
function CreateEPTasks($eid,$sguid,$createtime,$computername,$ip,$mac,$os){
    try{
        $clct = select_manage_collection('epinfo');
        //插入创建时间
        $info = array();
        $info['computerinfo']['createtime'] = $createtime;
        $result = $clct->update(array('eid' => $eid, 'sguid' => $sguid), array('$set' => $info['computerinfo']), array('upsert' => true));

    }catch(\Exception $e){
        Log::add("Error",array('ErrorMessage'=>$e->getMessage()));
    }

    try{
        //新增客户端执行自动入组
        $toGroup = new AutoGroup();
        $rv=$toGroup->autoInGroup($eid, $sguid);
        if(!is_int($rv))
        {
            Log::add("AutoGroupLog", $info['computerinfo']);
            Log::add("AutoGroupError",array('eid'=>$eid,'sguid'=>$sguid,'returnValue'=>$rv));
        }
    }catch(\Exception $e){
        Log::add("Error",array('ErrorMessage'=>$e->getMessage()));
    }

    try{
        //产生消息
        $msg = sprintf('b:%s:pf:ep:new',$eid);
        $title = '新终端'.$computername.'-'.$ip.'加入';
        $body = <<<BODY
            新终端加入中心 <br />
            终端名称：%s <br />
            IP地址：%s <br />
            MAC：%s <br />
            操作系统：%s <br />
            加入时间：%s <br />
BODY;
        $body = sprintf($body,
            $computername,
            $ip,
            $mac,
            $os,
            date("Y-m-d H:i:s")
        );

        //$ok = Common::makeMsg(json_encode([$msg]), '欢迎'.$info['computerinfo']['computername'].'-'.$info['computerinfo']['ip'].'加入瑞星安全云', '欢迎'.$info['computerinfo']['computername'].'加入瑞星安全云');
        $ok = Common::makeMsg(json_encode([$msg]), $body, $title);

        if(is_string($ok)){
            Log::add('ERROR', ['response'=>$ok]);
        }

    }catch(\Exception $e){
        Log::add("Error",array('ErrorMessage'=>$e->getMessage()));
    }

    //网盘开启策略
    $disk = empty(rds::hGet(CACHE_REDIS_ORG_PRE . $eid, 'disk_set'))?0:1;
    rds::lPush(REDIS_DISK_QUEUE, json_encode(array('eid'=>$eid,'sguid'=>$sguid,'disk'=>$disk)));

    updateIoaInfo( $sguid,$eid );
}

//客户端第一次连接中心，改变中心时调用
function updateIoaInfo( $sguid,$eid ){
    try {
        $aSguid = Ioa::getEidAndUseridBySguid( $sguid );
        if(is_array($aSguid) && !empty($aSguid)){//改变中心，改变团队
            $userid = $aSguid['userid'];
            $oldEid = $aSguid['eid'];
            $oldOrgid = Ioa::getOrgidByEid( $oldEid );
            $newOrgid = Ioa::getOrgidByEid( $eid );
            Ioa::changeIoaMemberOrg( $userid, $oldOrgid, $newOrgid ); //更换团队
            Ioa::updateSguidInfo( $sguid,$eid );
        }else{//第一次连接中心加入团队
            Ioa::terminalAddMember( $sguid,$eid );
        }
    }  catch (\Exception $e) {
        Log::add("updateIoaerror",array('ErrorMessage'=>$e->getMessage()));
    }

}

/**
 * 更新是否卸载状态
 * @param $mongo
 * @param $params
 * @param $info
 */
function updateEpUnset($params)
{
    $eid = $params['eid'];
    $sguid = $params['sguid'];
    $unset = $params['unset'];
    //Log::add("API Update Ep unset", $params);
    $clct = select_manage_collection('epinfo');
    $clct->update(array('eid' => $eid, 'sguid' => $sguid), array('$set' => array('unset'=>$unset)));
}

function updateEpHeartbeat()
{
    $fun = '
    function aa()
    {
        var date = "2016-05-06 08:52:50";
        date = new Date(date);
        date = date.getTime();
        date = date + 300 * 1000;
        date = new Date(date);
        var years = date.getFullYear();
        var months = date.getMonth()+1;
        months = "" + months;
        months = months.length==1? "0"+months:months;

        var days = ""+date.getDate();
        days = days.length==1? "0"+days:days;
        var hours = date.getHours();
        var min = date.getMinutes();
        var second = date.getSeconds();
        alert([years,months,days].join("-")+" "+[hours,min,second].join(":"));
    }';
}

function constructInfo($config, $data)
{
    $policy = $config['policy'];
    $cmd = $config['cmd'];
    $r = array();

    if (count($data['allpolicy']) > 0) {
        array_push($policy['content']['policycontent'], $data['allpolicy']);
    }
    if (count($data['grouppolicy']) > 0) {
        array_push($policy['content']['policycontent'], $data['grouppolicy']);
    }
    if (count($data['clientpolicy']) > 0) {
        array_push($policy['content']['policycontent'], $data['clientpolicy']);
    }

    if (count($data['allpolicy']) > 0 || count($data['grouppolicy']) > 0 || count($data['clientpolicy']) > 0) {
        array_push($r, $policy);
    }

    $allcmd = array_values($data['allcmd']);
    $groupcmd = array_values($data['groupcmd']);
    $clientcmd = array_values($data['clientcmd']);
    if (count($allcmd) > 0) {
        for ($i = 0; $i < count($allcmd); $i++) {
            array_push($cmd['content']['cmdcontent'], $allcmd[$i]);
        }
    }
    if (count($groupcmd) > 0) {
        for ($i = 0; $i < count($groupcmd); $i++) {
            array_push($cmd['content']['cmdcontent'], $groupcmd[$i]);
        }
    }
    if (count($clientcmd) > 0) {
        for ($i = 0; $i < count($clientcmd); $i++) {
            array_push($cmd['content']['cmdcontent'], $clientcmd[$i]);
        }
    }

    if (count($allcmd) > 0 || count($groupcmd) > 0 || count($clientcmd) > 0) {
        array_push($r, $cmd);
    }
    return $r;
}

function constructInfoNew($config, $data)
{
    $policy = $config['policy'];
    $cmd = $config['cmd'];
    $r = array();

    $hasPolicy = false;
    if (isset($data['policy']['allpolicy']) && count($data['policy']['allpolicy']) > 0) {
        foreach ($data['policy']['allpolicy'] as $apolicy) {
            array_push($policy['content']['policycontent'], $apolicy);
        }
        $hasPolicy = true;
    }

    if (isset($data['policy']['grouppolicy']) && count($data['policy']['grouppolicy']) > 0) {
        foreach ($data['policy']['grouppolicy'] as $gpolicy) {
            array_push($policy['content']['policycontent'], $gpolicy);
        }
        $hasPolicy = true;
    }

    if (isset($data['policy']['clientpolicy']) && count($data['policy']['clientpolicy']) > 0) {
        foreach ($data['policy']['clientpolicy'] as $cpolicy) {
            array_push($policy['content']['policycontent'], $cpolicy);
        }
        $hasPolicy = true;
    }

    if ($hasPolicy) {
        array_push($r, $policy);
    }

    $allcmd = $data['cmd'];
    if (count($allcmd) > 0) {
        //array_push($cmd['content']['cmdcontent'], $allcmd);
        $cmd['content']['cmdcontent'] = $allcmd;
        array_push($r, $cmd);
    }
    return $r;
}

function getIP()
{
    if (@$_SERVER["HTTP_X_FORWARDED_FOR"])
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if (@$_SERVER["HTTP_CLIENT_IP"])
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    else if (@$_SERVER["REMOTE_ADDR"])
        $ip = $_SERVER["REMOTE_ADDR"];
    else if (@getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (@getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (@getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else
        $ip = "Unknown";
    return $ip;
}

/**
 * 创建GUID
 * @return string
 */
function createGuid()
{
    $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $hyphen = '';//chr(45);// "-"
    $uuid = //chr(123)．// "{"
        substr($charid, 0, 8) . $hyphen
        . substr($charid, 8, 4) . $hyphen
        . substr($charid, 12, 4) . $hyphen
        . substr($charid, 16, 4) . $hyphen
        . substr($charid, 20, 12);
    //.chr(125);// "}"
    return $uuid;
}

/**
 * 获取毫秒级时间戳
 * @return int
 */
//function getTimestamp()
//{
//    list($ms, $sec) = explode(' ', microtime());
//    $msec = intval((floatval($sec) + floatval($ms)) * 1000);
//    return $msec;
//}