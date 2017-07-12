<?php
/**
 * Created by PhpStorm.
 * User: xujy
 * Date: 2017/1/5
 * Time: 9:45
 */
class HomeModel
{
    protected $db_obj;
    protected $epinfo;
    public function __construct(){
        $this->db_obj = new DbProcess();
        $this->epinfo = select_manage_collection('epinfo');
    }

    //eid总数
    public function countEid(){
        $db_table = 'rs_esm_soho.esm_user';
        return $this->db_obj->getCount($db_table);
    }

    //eid新增数/小时;按整点统计前24个点，每小时的eid数
    public function hoursEid(){
        $aRes = array();
        $endtime = date('Y-m-d H:00:00');
        for($i=0;$i<24;$i++){
            $key = date('Y-m-d H:00:00',strtotime("-$i hour"));
            $aRes[$key] = 0;
        }
        $starttime = date('Y-m-d H:00:00',strtotime("-1 day"));
        $fields = "DATE_FORMAT(CreateTime,'%Y-%m-%d %H:00:00') AS hour,COUNT('UserID') AS num";
        $db_table = 'rs_esm_soho.esm_user';
        $aWhere = array('largeequal_CreateTime'=>$starttime,'lessequal_CreateTime'=>$endtime);
        $groupBy = " DATE_FORMAT(CreateTime,'%Y-%m-%d %H:00:00')";
        $aHourEid = $this->db_obj->getList($db_table,$fields,$aWhere,'','','','',$groupBy);
        if(is_array($aHourEid) && !empty($aHourEid)){
            foreach( $aHourEid as  $aEid){
                $resKey = $aEid['hour'];
                $aRes[$resKey] = $aEid['num'];
            }
        }
        return $aRes;
    }

    //客户端总数
    public function clientTotal(){
        return $this->epinfo->count();
    }

    //客户端新增数/小时;按整点统计前24个点,每个小时的客户端安装数
    public function hoursClient(){
        $aRes = array();
        $aNewRes = array();
        $k = 0;
        for($i=0;$i<24;$i++){
            $key = date('Y-m-d H:00:00',strtotime("-$i hour"));
            $starttime = $key;
            $endtime = date('Y-m-d H:59:59',strtotime("-$i hour"));
            $aRes[$k]['hour'] = $key;
            $aRes[$k]['starttime'] = $starttime;
            $aRes[$k]['endtime'] = $endtime;
            $k++;
        }

        foreach($aRes as $res ){
            $newKey = $res['hour'];
            $num = $this->epinfo->find(array("edate"=>array('$gt'=>$res['starttime'],'$lt'=>$res['endtime'])))->count();
            $aNewRes[$newKey] = $num > 0 ?$num:0;
        }
        return $aNewRes;
    }

    //客户端在线总数
    public function clientOnline(){
        return $this->epinfo->find( array("unset"=>0) )->count();
    }

    //客户端卸载总数
    public function clientOffline(){
        return $this->clientTotal() - $this->clientOnline();
    }

    /*windows,android 客户端在线，卸载总数
    *$platform:windows,android
    *$val=0:在线  $val=1:卸载
    */
    public function clientOnOffLine( $platform,$val ){
        return $this->epinfo->find( array("systype"=>$platform,"unset"=>$val) )->count();
    }

}