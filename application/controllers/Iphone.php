<?php
class IphoneController extends MyController
{
    public function init()
    {
        parent::init();
        Yaf_Dispatcher::getInstance()->disableView();
    }

    public function indexAction()
    {
        $sguid = $this->param('sguid', '');
        $eid = $this->param('eid', '');

        if(empty($sguid) || empty($eid)){
            return;
        }
        $objRising = new RsEncDec();
        $encEid = $objRising->rsdecode( $eid );
        $encSguid  = $objRising->rsdecode( $sguid );

        $objPhone = new IphoneModel();
        $objPhone->uninstall( $encEid,$encSguid );

        $this->disply('Index/uninstall');
    }

}