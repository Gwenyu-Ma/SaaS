<?php
use \Lib\Store\MysqlCluster as MC;
use \Lib\Store\MysqlWriteCluster as MW;

use Lib\Store\Mysql;
use Lib\Model\RedisDataManager;

class IoaSguidModel
{
    protected $db_obj;
    private $prefilx = 'locate_';
    private $field = '';

    public function __construct(){
        $this->db_obj = new DbProcess();
    }

    public function getDiskAccessBySguid( $sguid ){
        $this->field = $this->prefilx . 'sguid';
        return $this->db_obj->getListOne("sguid_userid", array(
            "disk_access"
        ), array(
            $this->field => $sguid,
        ));
    }

    public function setDiskAccess( $eid,$sguid,$disk_access ){
        $data=[
            'eid'=>$eid,
            'objid'=>$sguid,
            'grouptype'=>2,
            'productid'=>'DB8F440F-3717-47f6-8922-4D9D6A89B824',
            'policytype'=>2,
            'policyjson'=>json_encode(['root'=>['openxoa'=>['@value'=>$disk_access,'@attribute'=>['admin'=>1]]]]),
            'desp'=>''
        ];
        $policy=new PolicyModel();
        $result=$policy->editPolicy($data);
        return is_bool($result) && $result;
    }
}