<?php

class PTypeController extends MyController
{
    public function init()
    {
        parent::init();
        parent::includeGlobalConfig();

    }

    /**
     * 获取产品列表信息
     * @param groupid 组编号
     * @param sguid 客户端编号
     */
    public function getProductListAction()
    {
        $groupid = isset($_REQUEST['groupid']) ? $_REQUEST['groupid'] : "";
        $sguid = isset($_REQUEST['sguid']) ? $_REQUEST['sguid'] : "";

        $model = new PTypeModel($this->_global_config);
        $productinfo = $model->GetProductList($sguid,$groupid);
        if (!empty($productinfo)) {
            Common::out(Common::returnAjaxMsg(0, 0, "", $productinfo));
        } else {
            Common::out(Common::returnAjaxMsg(0, 0, "", ""));
        }
    }

    /**
     * 获取产品类型信息
     * @param id string 产品的序列号
     */
    public function getPTypeAction()
    {
        $id = $_REQUEST['id'];
        $model = new PTypeModel($this->_global_config);
        $productinfo = $model->GetProductType($id);
        if (!empty($productinfo)) {
            Common::out(Common::returnAjaxMsg(0, 0, "", $productinfo));
        } else {
            Common::out(Common::returnAjaxMsg(0, 0, "", ""));
        }
    }
}