<?php
use \Lib\Store\MongoClient;
class PTypeModel
{
    private $_eid;
    private $_rc4;

    private $product_config;

    public function __construct($config = array())
    {
        $this->_rc4 = new rc4();
        $this->_eid = $this->_rc4->decrypt(Common::cookie('EID'));
        $this->policyinfo = select_manage_collection('policyinfo');
        $this->product_config = $config['PRODUCT_GUID_INFO'];
    }

    public function GetProductList( $sguid ,$groupid )
    {

        $where = $f = $baseinfo_arr = array();
        if (isset($sguid) && $sguid != '') {
            $where['grouptype'] = 2;
            $where['policyobject'] = $sguid;
        }

        if (isset($groupid) && intval($groupid) > 0) {
            $where['grouptype'] = 1;
            $where['policyobject'] = intval($groupid);
        }

        $rows = Common::mongoResultToArray($this->policyinfo->find( $where ));

        if (!empty($rows)) {
            foreach ($rows as $key => $val) {//var_dump($val);exit;
                if(isset( $val['productid']) ){
                    array_push($f, $val['productid']);
                }
            }
        }



        if (!empty($f)) {
            foreach ($f as $key => $value) {
                if (isset($this->product_config[$value])) {
                    $baseinfo_arr[] = array('name' =>$this->product_config[$value]['NAME'] , 'value' => $value);
                }
            }
        } else {
            foreach ($this->product_config as $key => $value) {
                $baseinfo_arr[] = array('name' => $value['NAME'], 'value' => $key);
            }
        }

        if (!empty($baseinfo_arr)) {
            return $baseinfo_arr;
        }
        return array();
    }

    public function GetProductType($id)
    {

        if (isset($id)) {
            $productinfo = array('name' => $this->product_config[$id]['NAME'], 'value' => $id);
            return $productinfo;
        }
        return array();
    }
}