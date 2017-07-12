<?php

class XavController extends MyController
{
    public function initXavAction()
    {
        $eid=$this->param('eid',null);
        XavModel::initXav($eid);
        $this->ok(null);
    }


    public function initRfwTFAAction()
    {
        $eid=$this->param('eid',null);
        XavModel::initRfwTFA($eid);
        $this->ok(null);
    }

    public function initRfwUrlByResultAction()
    {
        $eid=$this->param('eid',null);
        XavModel::initRfwUrlByResult($eid);
        $this->ok(null);
    }

    public function initPhoneSpamAction()
    {
        $eid=$this->param('eid',null);
        XavModel::initPhoneSpam($eid);
        $this->ok(null);
    }

    public function initRfwBNSAction()
    {
        $eid=$this->param('eid',null);
        XavModel::initRfwBNS($eid);
        $this->ok(null);
    }
}