<?php
use \Lib\Store\MysqlCluster as MC;
use \Lib\Store\MysqlWriteCluster as MW;

use Lib\Store\Mysql;
use Lib\Model\RedisDataManager;

class IoaOrgidModel
{
    protected $db_obj;
    private $prefilx = 'locate_';
    private $field = '';

    public function __construct()
    {
        $this->db_obj = new DbProcess();
    }

    public function insertOrgid( $params_org ){
        $eid =  $params_org['eid'];
        $this->field = $this->prefilx . 'eid';
        $aOrgid =  $this->db_obj->getListOne("eid_orgid", array(
            "orgid",
        ), array(
            $this->field => $eid,
        ));
        if(is_array($aOrgid) && !empty($aOrgid)){
            return false;
        }
        $userid = $this->db_obj->insertTab('eid_orgid', $params_org);
        if (!$userid) {
            return false;
        }
        return true;
    }

    public function testinsertOrgid( $params_org ){
        $eid =  $params_org['eid'];
        $this->field = $this->prefilx . 'eid';
        $aOrgid =  $this->db_obj->getListOne("test_eid_orgid", array(
            "orgid",
        ), array(
            $this->field => $eid,
        ));
        if(is_array($aOrgid) && !empty($aOrgid)){
            return false;
        }
        $userid = $this->db_obj->insertTab('test_eid_orgid', $params_org);
        if (!$userid) {
            return false;
        }
        return true;
    }

    public function getCenterDiskSet( $eid ){
        $disk_set = RedisDataManager::getIoaCenterDisk( $eid );
        if(empty($disk_set)){
            $this->field = $this->prefilx . 'eid';
            $aEid =  $this->db_obj->getListOne("eid_orgid", array(
                "disk_set",
            ), array(
                $this->field => $eid,
            ));
            $disk_set = $aEid['disk_set'];
            empty($disk_set)?$disk_set=0:$disk_set=$disk_set;
            RedisDataManager::setIoaCenterDisk($eid,$disk_set);
        }
        return $disk_set;
    }

    public function setCenterDisk( $eid,$disk_set ){
        RedisDataManager::setIoaCenterDisk( $eid,$disk_set );
        $result = $this->db_obj->updateTab('eid_orgid', array(
            "disk_set" => $disk_set
        ), array(
            "locate_eid" => $eid,
        ));
        if ($result) {
            return true;
        } else {
            return false;
        }

    }

    public function getOrgidByEid( $eid ){
        $orgid = RedisDataManager::getIoaorgid($eid);
        if(empty( $orgid )){
            $this->field = $this->prefilx . 'eid';
            $aOrgid =  $this->db_obj->getListOne("eid_orgid", array(
                "orgid",
            ), array(
                $this->field => $eid,
            ));
            $orgid = $aOrgid['orgid'];
            RedisDataManager::setIoaorgid($eid,$orgid);
        }

        return $orgid;
    }

    public function getUseridAndOrgidByEid( $eid ){
        $this->field = $this->prefilx . 'eid';
        return $this->db_obj->getListOne("eid_orgid", array(
            "userid","orgid"
        ), array(
            $this->field => $eid,
        ));
    }

    public function updateDataByEid( $eid,$orgId,$userid ){
        $result = $this->db_obj->updateTab('eid_orgid', array(
            "orgid" => $orgId,
            "userid" =>$userid
        ), array(
            "locate_eid" => $eid,
        ));
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}
