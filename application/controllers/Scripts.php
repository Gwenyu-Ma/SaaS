<?php
use Lib\Store\RedisCluster as Redis;
use Lib\Model\RedisDataManager;
use Lib\Util\Ioa;
use Lib\Util\Log;
use \Lib\Store\Mysql;

class ScriptsController extends Yaf_Controller_Abstract
{
    public function init()
    {
        Yaf_Dispatcher::getInstance()->disableView();
        $this->request = Yaf_Dispatcher::getInstance()->getRequest();
    }
    public function diskAction()
    {
        for (; ;) {
            $data = Redis::rPop(REDIS_DISK_QUEUE);
            if ($data === false) {
                break;
            }
            $data = json_decode($data, true);
            try {
                $objIoa = new IoaSguidModel();
                $ok = $objIoa->setDiskAccess($data['eid'], $data['sguid'], $data['disk']);
                if (!$ok) {
                    Log::add(REDIS_DISK_QUEUE . '_ERROR', $data);
                } else {
                    Log::add(REDIS_DISK_QUEUE . '_OK', $data);
                }
            } catch (\Exception $e) {
                $data['Exception'] = $e;
                Log::add(REDIS_DISK_QUEUE . '_ERROR', $data);
            }finally{
                echo $data['sguid'];
            }
        }
    }

	//从任务中获取调用IOA失败的username，eid，再次尝试去IOA获取orgid，userid
    public function getIoaOrgidAction(){
        for(;;) {
            $data = Redis::rPop(REDIS_IOA_ORGID_QUEUE);
            if($data === false){
                break;
            }
            $data = json_decode($data, true);
            try{
                $eid = $data['eid'];
                $str_create_time = date("Y-m-d H:i:s",time());
                $objIoa = Ioa::createIoaOrg( $data['username'] );
                if($objIoa->data->resultCode !== '0000' && $objIoa->data->resultCode !== '3003' ){
                    Redis::lPush(REDIS_IOA_ORGID_QUEUE, json_encode(array('eid'=>$eid,'username'=>$data['username'])));
                    return;
                }
                $ioa_orgCode = $objIoa->data->orgCode;
                $ioa_userId = $objIoa->data->userId;
                $ioaModel = new IoaOrgidModel();
                $ioa_data = array(
                    'eid'     =>  $eid ,
                    'orgid'   =>   $ioa_orgCode,
                    'userid'  =>   $ioa_userId,
                    'disk_set' =>0,
                    'add_time' =>  $str_create_time
                );
                RedisDataManager::setIoaorgid($eid,$ioa_orgCode);
                RedisDataManager::setIoaCenterDisk($eid,0);
                if(!$ioaModel->insertOrgid( $ioa_data )){
                    Log::add("ScriptInsertOrgid",array("msg" => "Script Insert $eid Orgid ERROR"));
                    return;
                }
            }catch(\Exception $e){
                Redis::lPush(REDIS_IOA_ORGID_QUEUE, json_encode(array('eid'=>$data['eid'],'username'=>$data['username'])));
                Log::add("ScriptGetIoaOrgidError",array("msg" => 'Script Ioa Orgid is NULL'));
                return;
            }
        }
    }

    //从任务中获取修改企业名称时调用IOA失败的队列
    public function modidyIoaCompanyAction(){
        for(;;) {
            $data = Redis::rPop(REDIS_IOA_COMPANY_QUEUE);
            if ($data === false) {
                break;
            }
            $data = json_decode($data, true);
            try {
                $eid = $data['eid'];
                $objIoa = new IoaOrgidModel();
                $ioaOrgCode = $objIoa->getOrgidByEid( $eid );
                $ioaModel = Ioa::changeIoaOrgName( $ioaOrgCode,$data['company'] );
                if( $ioaModel->data->resultCode !== '0000' ){
                    Redis::lPush(REDIS_IOA_COMPANY_QUEUE, json_encode(array('eid'=>$eid,'company'=>$data['company'])));
                }
            }catch(\Exception $e){
                Redis::lPush(REDIS_IOA_COMPANY_QUEUE, json_encode(array('eid'=>$data['eid'],'company'=>$data['company'])));
                Log::add("ScriptModifyIoaOrgidError",array("msg" => 'Script Modify Ioa Orgid Error'));
            }
        }
    }

    //从任务中获取修改管理员时调用IOA失败的队列
    public function modidyIoaAction(){
        for(;;) {
            $data = Redis::rPop(REDIS_IOA_ADMIN_QUEUE);
            if ($data === false) {
                break;
            }
            $data = json_decode($data, true);
            try {
                $eid = $data['eid'];
                $objIoa = new IoaOrgidModel();
                $objIoa = $objIoa->getOrgidByEid( $eid );
                $aEid = $objIoa->getUseridAndOrgidByEid( $eid );
                if(empty($aEid)){
                    Redis::lPush(REDIS_IOA_ADMIN_QUEUE, json_encode(array('eid'=>$eid,'newAdmin'=>$data['newAdmin'])));
                    return;
                }
                $orgId = $aEid['orgid'];
                $userid = $aEid['userid'];
                $aNewUser = Ioa::addIoaOrgMember($orgId,$data['newAdmin']);
                if( $aNewUser->resultCode !== '0000'){
                    Redis::lPush(REDIS_IOA_ADMIN_QUEUE, json_encode(array('eid'=>$eid,'newAdmin'=>$data['newAdmin'])));
                }
                $newOrgCode = $aNewUser->data->orgCode;
                $newUserId = $aNewUser->data->userId;
                $resUpdate = $objIoa->updateDataByEid( $eid,$newOrgCode,$newUserId );
                if( !$resUpdate ){
                    Log::add("ScriptModifyIoaAdminError",array("msg" => 'Script Modify Ioa Admin Error'));
                    return;
                }
            }catch(\Exception $e){
                Redis::lPush(REDIS_IOA_ADMIN_QUEUE, json_encode(array('eid'=>$data['eid'],'newAdmin'=>$data['newAdmin'])));
                Log::add("ScriptModifyIoaAdminError",array("msg" => 'Script Modify Ioa Admin Error'));
            }
        }
    }

    //读取所有的eid写入redis中
    public function getAllEidAction(){
        $aEid = Mysql::getAll('select EID from esm_user ');
        if(is_array($aEid) && !empty($aEid)){
            foreach( $aEid as $eid ){
                Redis::sAdd('getalleid',$eid['EID']);
            }
            //echo Redis::sCard("getalleid");
            //$aRes = Redis::sMembers('getalleid');
        }
    }

    //把esm_user表中的EID批量绑定IOA
    public function eidBindIoaAction(){
        $i = 0;
        $aEid = Mysql::getAll('SELECT EID,UserName FROM esm_user WHERE EID NOT IN (SELECT eid FROM eid_orgid) ');
        if(is_array($aEid) && !empty($aEid)){
            echo '开始要初始化EID!<br />';
            foreach( $aEid as $eid ){
                $objIoa = Ioa::createIoaOrg( $eid['UserName']);
                $i++;
                if($objIoa->data->resultCode !== '0000' && $objIoa->data->resultCode !== '3003'){
                    echo '初始化第 ' . $i .'个EID：'.$eid['EID'].' 失败<br />';
                    continue;
                    //Redis::lPush(REDIS_IOA_ORGID_QUEUE, json_encode(array('eid'=>$eid['EID'],'username'=>$eid['UserName'])));
                }else{
                    $str_create_time = date("Y-m-d H:i:s",time());
                    $esm_eid = $eid['EID'];
                    $ioa_orgCode = $objIoa->data->orgCode;
                    $ioa_userId = $objIoa->data->userId;
                    $ioaModel = new IoaOrgidModel();
                    $ioa_data = array(
                        'eid'     =>  $esm_eid ,
                        'orgid'   =>   $ioa_orgCode,
                        'userid'  =>   $ioa_userId,
                        'disk_set' =>0,
                        'add_time' =>  $str_create_time
                    );
                    //RedisDataManager::setIoaorgid($esm_eid,$ioa_orgCode);
                    //RedisDataManager::setIoaCenterDisk($esm_eid,0);
                    if(!$ioaModel->insertOrgid( $ioa_data )){
                        echo '初始化第 ' . $i .'个EID：'.$eid['EID'].' 写入失败<br />';
                        continue;
                    }
                    echo '初始化第 ' . $i .'个EID：'.$eid['EID'].' 成功<br />';
                }
            }
            echo '初始化EID结束<br />';
        }else{
            echo '没有初始化eid！';
        }
        return;
    }

    //终端批量绑定IOA  执行此方法前，先执行上面的 eidBindIoaAction()方法
    public function sguidBindIoaAction(){
        $this->db_obj = new DbProcess();
        //$inSguid = array();
        $sguid = array();
        $eid = array();
        $aSguid = Mysql::getAll('SELECT eid,sguid FROM test_sguid_userid ');
        if(is_array($aSguid) && !empty($aSguid)){
            $k = 0;
            foreach( $aSguid as $val ){
                $sguid[$k] = $val['sguid'];
                $eid[$k] = $val['eid'];
                $k++;
             }
        }
        $ep = select_manage_collection('epinfo');
        $aRes = $ep->find(['sguid' => ['$nin' => $sguid],'unset'=>0],['eid'=>true,'sguid' => true])->sort(['edate'=>-1]);
        //$num = 554,562;
        $eps = Common::mongoResultToArray($aRes);

        if(is_array( $eps ) && !empty( $eps )){
            foreach( $eps as $v ){
                $eid = $v['eid'];
                $sguid = $v['sguid'];
                $sql = "SELECT sguid FROM test_sguid_userid WHERE sguid='$sguid'";
                $oneId =  Mysql::getRow($sql);
                if($oneId['sguid']){
                    continue;
                }

                $current_time = date("Y-m-d H:i:s");
                $orgid = Ioa::getOrgidByEid( $eid );
                if(empty($orgid)){
                    continue;
                }
                //echo 'orgid:'.$orgid.' sguid:'.$sguid;
                $ObjMember = Ioa::addIoaOrgMember( $orgid,$sguid );
               //print_r( $ObjMember );
                //exit();
                $userId = $ObjMember->data->userId;
                if( empty($userId)){
                    continue;;
                }
                $disk_access = 0;
                Mysql::exec("INSERT INTO test_sguid_userid (sguid,eid,userid,add_time,disk_access) VALUES ('$sguid', '$eid','$userId','$current_time',$disk_access)");
            }
        }

    }

    //手动执行一个sguid绑定IOA
    public function sguidBindOneIoaAction(){
        $eid = '0903109467907378';
        $sguid = '866A06AC4F5EE839561B7AD07F2A53E5';
        Ioa::terminalAddMember($sguid,$eid);
    }

        //手动执行eid绑定IOA
    public function registerOneManagerAction(){
            $eid = '3710F18686560358';
            $username = 'wh19960113@qq.com';
            try{
                $str_create_time = date("Y-m-d H:i:s",time());
                $objIoa = Ioa::createIoaOrg( $username );
                /*if($objIoa->data->resultCode !== '0000' ){
                    Redis::lPush(REDIS_IOA_ORGID_QUEUE, json_encode(array('eid'=>$eid,'username'=>$username)));
                    return;
                }*/
                $ioa_orgCode = $objIoa->data->orgCode;
                $ioa_userId = $objIoa->data->userId;
                $ioaModel = new IoaOrgidModel();
                $ioa_data = array(
                    'eid'     =>  $eid ,
                    'orgid'   =>   $ioa_orgCode,
                    'userid'  =>   $ioa_userId,
                    'disk_set' =>0,
                    'add_time' =>  $str_create_time
                );
                RedisDataManager::setIoaorgid($eid,$ioa_orgCode);
                RedisDataManager::setIoaCenterDisk($eid,0);
                if(!$ioaModel->insertOrgid( $ioa_data )){
                    Log::add("ScriptInsertOrgid",array("msg" => "Script Insert $eid Orgid ERROR"));
                    return;
                }
            }catch(\Exception $e){
               // Redis::lPush(REDIS_IOA_ORGID_QUEUE, json_encode(array('eid'=>$eid,'username'=>$username)));
                Log::add("ScriptGetIoaOrgidError",array("msg" => 'Script Ioa Orgid is NULL'));
                return;
            }
        }


}

