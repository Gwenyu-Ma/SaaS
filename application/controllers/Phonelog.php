<?php
/**
 *
 */
class PhonelogController extends MyController
{
    private $input;
    public function init()
    {
        parent::init();
        $this->input = json_decode(file_get_contents('php://input'));
    }

    public function helloAction()
    {
        echo 'hello';
    }

    /**
     * 获取护客户端概览
     */
    public function getClientsOverviewAction()
    {
        $objId = $this->param('objId', '');

        if (is_null($objId)) {
            $this->notice('参数错误。', 1);
            return;
        }
        $argArr = ['objId' => $objId];
        $argArr['name'] = $this->param('name', '');
        $argArr['ip'] = $this->param('ip', '');
        $argArr['mac'] = $this->param('mac', '');
        $argArr['sys'] = $this->param('sys', '');
        $argArr['os'] = $this->param('os', '');
        $argArr['vlibver'] = $this->param('vlibver', '');
        $argArr['loc'] = $this->param('loc', -1);
        $argArr['spam'] = $this->param('spam', -1);
        $argArr['time_spam'] = $this->param('time_spam', -1);
        $argArr['productIds'] = ['74F2C5FD-2F95-46be-B67C-FFA200D69012'];
        $argArr['onlinestate']=$this->param('onlinestate',-1);
        $columns = array('vlibver', 'loc', 'spam', 'time_spam');
        $phoneLog = new PhoneLogModel();
        $result = $phoneLog->getClientsOverview($this->_eid, $argArr, ['andvirusdbversion', 'andloc', 'andspam', 'andtimespam']);

        $this->ok($result, '成功。');
    }

    private function checkInputNopaging()
    {
        if (isset($this->input->objtype) && !in_array($this->input->objtype, [0, 1, 2])) {
            $this->notice('参数错误。', 1);
            return false;
        }
        if (empty($this->input->objid)) {
            $this->notice('参数错误。', 1);
            return false;
        }

        if (empty($this->input->queryconditions)) {
            $this->notice('参数错误。', 1);
            return false;
        }
        if (empty($this->_eid)) {
            $this->notice('登录过期。', 1);
            return false;
        }
        if (empty($this->input->viewtype)) {
            $this->notice('参数错误。', 1);
            return false;
        }
        return true;
    }

    private function checkInput()
    {
        if(!$this->checkInputNopaging()){
            return false;
        }
        if (empty($this->input->paging)) {
            $this->notice('参数错误。', 1);
            return false;
        }
        return true;
    }
    public function getSpamMsgLogAction()
    {
        if(!$this->checkInput()){
            return;
        }
        $result = (new PhoneLogModel())->getSpamMsgLog($this->_eid, $this->input->objtype, $this->input->objid, $this->input->queryconditions, $this->input->paging);
        $this->ok($result);
    }

    public function getSpamPhoneLogAction()
    {
        if(!$this->checkInput()){
            return;
        }
        $result = (new PhoneLogModel())->getSpamPhoneLog($this->_eid, $this->input->objtype, $this->input->objid, $this->input->queryconditions, $this->input->paging);
        $this->ok($result);
    }

    public function getScanEventLogAction()
    {
        if(!$this->checkInput()){
            return;
        }
        $result = (new PhoneLogModel())->getScanEventLog($this->_eid, $this->input->objtype, $this->input->objid, $this->input->queryconditions, $this->input->paging);
        $this->ok($result);
    }

    public function getVirusLogAction()
    {
        if(!$this->checkInput()){
            return;
        }
        $viewType = $this->input->viewtype;

        if($viewType==='xav'){
            $result = (new PhoneLogModel())->getVirusByVirus($this->_eid, $this->input->objtype, $this->input->objid, $this->input->queryconditions, $this->input->paging);

        }else{
            $result = (new PhoneLogModel())->getVirusLog($this->_eid, $this->input->objtype, $this->input->objid, $this->input->queryconditions, $this->input->paging);
        }
        $this->ok($result);
    }

    public function getPhoneSpamAction()
    {
        $model = new PhoneLogModel();
        $data = $model->getPhoneSpam($this->_eid);

        $this->ok($data);
    }

    public function getPhonelocalAction()
    {
        if(!$this->checkInputNopaging()){
            return;
        }
        $result = (new PhoneLogModel())->getPhonelocal($this->_eid, $this->input->objtype, $this->input->objid, $this->input->queryconditions);
        $this->ok($result);
    }

}
