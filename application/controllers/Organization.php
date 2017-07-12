<?php
class OrganizationController extends MyController
{
    public function init()
    {

        parent::init();
        Yaf_Dispatcher::getInstance()->disableView();
    }
    public function testOrganizationAction()
    {
        $this->getOrganizationAction();
    }

    /**
     * *
     * 获取企业信息
     */
    public function getOrganizationAction()
    {
        $orgModel = new OrganizationModel();
        $org = $orgModel->getOrganizationById($this->_eid);
        if (empty($org)) {
            $this->notice("企业信息不存在。", 2);
            return;
        }
        $this->ok($org, "成功。");
    }
    public function testUpdateOrganizationAction()
    {
        $this->updateOrganizationAction();
    }

    /**
     * 更新企业信息
     */
    public function updateOrganizationAction()
    {       
        if (empty($this->param('id', '')) || empty($this->param('oName', ''))) {
            $this->notice("参数错误。", 1);
            return;

        }
        $org = [
            'EID' => $this->_eid,
            'OID' => $this->param('id', ''),
            'OName' => $this->param('oName', ''),
            'Contact' => $this->param('contact', ''),
            //'OType' => $params['oType'],
            'Tel' => $this->param('tel', ''),
            "Addr" => $this->param('addr'),
            "ZipCode" => $this->param('zipCode', ''),
        ];
        // $org = array(
        //     'EID' => $this->_eid,
        //     'OID' => '259',
        //     'OName' => 'aaa',
        //     'Contact' => '123456',
        //     'Tel' => '123465',
        //     'Addr' => 'dfdffd',
        //     'ZipCode' => '000000',
        // );
        $orgModel = new OrganizationModel();
        if ($orgModel->updateOrganization($org)) {
            $this->ok(null, "成功。");
            return;
        }
        $this->notice("更新企业信息失败。", 2);
    }
}
