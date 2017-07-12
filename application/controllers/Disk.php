<?php
class DiskController extends MyController
{
    public function init()
    {
        parent::init();
        Yaf_Dispatcher::getInstance()->disableView();
    }

    public function getIoaAccessListAction(){

        $limit = isset($_POST['limit']) ? $_POST['limit'] : '';
        $offset = isset($_POST['offset']) ? $_POST['offset'] : '';
        $argc = array(
            'limit' => $limit,
            'offset' => $offset,
            'productIds'=>['DB8F440F-3717-47f6-8922-4D9D6A89B824']
        );

        $eid = $this->_eid;
        $model = new GroupModel();
        $aRes = $model->GetGroupComputer(0,$argc,$eid);
        $i = 0;
        foreach( $aRes['rows'] as $res){
            $sguid=$res['sguid'];
            $policy=(new PolicyModel())->getPolicy($eid,$sguid, 'DB8F440F-3717-47f6-8922-4D9D6A89B824', 2, 2);

            $policyObj=empty($policy)? null:json_decode($policy['policyjson'],true);
            $aRes['rows'][$i]['disk_access']=empty($policyObj)? 0:$policyObj['root']['openxoa']['@value'];
            $i++;
        }

        $objIoaEid = new IoaOrgidModel();
        $disk_set = $objIoaEid->getCenterDiskSet( $eid );
        $aRes['disk_set'] = $disk_set;
        $this->ok( $aRes );
    }

    public function setIoaDiskAction(){
        $eid = $this->_eid;
        $disk_set = $this->param('disk_access', '0');
        $objIoaEid = new IoaOrgidModel();
        $bRes = $objIoaEid->setCenterDisk( $eid,$disk_set);
        if(!$bRes){
            $this->notice("网盘默认启动失败。", 1);
        }else{
            $this->ok('','网盘默认启动成功');
        }
    }

    public function setDiskAccessAction(){
        $sguid = $this->param('sguid', '');
        $disk_access = $this->param('disk_access', '0');
        $objIoa = new IoaSguidModel();
        $res = $objIoa->setDiskAccess($this->_eid, $sguid,$disk_access );
        if(!$res){
            $this->notice("终端网盘设置更改失败。", 1);
        }else{
            $this->ok('','终端网盘设置成功');
        }
    }
}