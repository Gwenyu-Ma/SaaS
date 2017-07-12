<?php
use Lib\Util\Log;
use Lib\Util\Ioa;
use Lib\Store\MongoClient;
use Lib\Model\RedisDataManager;

class IoaInitController extends Yaf_Controller_Abstract
{
    public function initOrgid(){
        return ;
        $aHasEid = array();
        $obj = new IoaInitModel();
        $aHaveEid = $obj->getHaveEid();
        foreach($aHaveEid as $haveEid){
            $aHasEid[] = $haveEid['eid'];
        }

        $aEid = $obj->getAllEid();
        foreach( $aEid as $eid ){
            $str_create_time = date("Y-m-d H:i:s",time());
            if(!in_array($eid['EID'],$aHasEid)){
                $objIoa = Ioa::createIoaOrg( $eid['UserName']);
                if($objIoa->data->resultCode !== '0000' ){
                    Log::add("getIoaOrgid",array("msg" => 'get ioa orgid error'));
                    return;
                }

                $ioa_orgCode = $objIoa->data->orgCode;
                $ioa_userId = $objIoa->data->userId;
                $ioaModel = new IoaOrgidModel();
                $ioa_data = array(
                    'eid'     =>   $eid['EID'],
                    'orgid'   =>   $ioa_orgCode,
                    'userid'  =>   $ioa_userId,
                    'disk_set' =>0,
                    'add_time' =>  $str_create_time
                );

                if(!$ioaModel->insertOrgid( $ioa_data )){
                    Log::add("insertIoaOrgid",array("msg" => 'insert eid_orgid error'));
                    return;
                }

                RedisDataManager::setIoaorgid($eid['EID'],$ioa_orgCode);
                RedisDataManager::setIoaCenterDisk($eid['EID'],0);
            }else{
                continue;
            }
        }
    }

    public function initUserid(){
        return;
        $obj = new IoaInitModel();
        $obj->initEps();
    }
}