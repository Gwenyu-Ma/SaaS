<?php
use Lib\Model\RedisDataManager;
use \Lib\Store\MysqlCluster as MC;
use \Lib\Store\MysqlWriteCluster as MW;
use \Lib\Store\RedisCluster as Redis;
use Lib\Util\Log;
use Lib\Store\Mysql;
use \Lib\Util\Ioa;
use \Lib\Store\MongoClient;

class IoaInitModel
{
    protected $db_obj;
    protected $redis;
    private $prefilx = "locate_";
    private $field = "";

    public function __construct()
    {
        $this->db_obj = new DbProcess();
        $this->redis = Redis::getInstance();
    }

    public function getAllEid(){
        $result = $this->db_obj->getList("esm_user", array(
            'EID',
            'UserName'
        ));
        return $result;
    }

    public function getHaveEid(){
        $result = $this->db_obj->getList("eid_orgid", array(
            'eid'
        ));
        return $result;
    }

    public function initEps(){
        $clct = select_manage_collection('epinfo');
        $eps = $clct->find(['unset' => 0],['eid'=>true,'sguid' => true]);
        foreach ($eps as $v) {
            $eid = $v['eid'];
            $sguid = $v['sguid'];
            $this->updateIoaInfo($sguid,$eid);
        }
    }


    public function updateIoaInfo( $sguid,$eid ){
        try {
            Ioa::terminalAddMember( $sguid,$eid );
        }  catch (\Exception $e) {
            Log::add("updateIoaerror",array('ErrorMessage'=>$e->getMessage()));
        }
    }

}