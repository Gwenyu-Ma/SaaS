<?php

use Lib\Store\MysqlCluster as MC;
class XAVLogController extends MyController
{
    //初始化
    private $_dbhost;
    private $_dbname;
    public $input;

    public function init()
    {
        parent::init();

        $this->input = json_decode(file_get_contents('php://input'));
        MC::$eid = $this->_eid;

    }

    #region xav概览
    /**
     * 获取病毒防护客户端概览
     */
    public function getClientsOverviewAction()
    {
        $objId = $this->param('objId', '');

        if (empty($objId)) {
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
        $argArr['filemon'] = $this->param('filemon', -1);
        $argArr['mailmon'] = $this->param('mailmon', -1);
        $argArr['sysmon'] = $this->param('sysmon', -1);
        $argArr['VirusAction'] = $this->param('VirusAction', -1);
        $argArr['onlinestate']=$this->param('onlinestate',-1);
        $argArr['productIds'] = ['D49170C0-B076-4795-B079-0F97560485AF', 'A40D11F7-63D2-469d-BC9C-E10EB5EF32DB'];
        $columns = array('filemon', 'mailmon', 'sysmon', 'virusaction', 'vlibver');

        $xavLog = new XAVLogModel($this->_eid);
        $result = $xavLog->GetClientsOverview($this->_eid, $argArr, $columns);
        $this->ok($result, '成功。');
    }

    public function testGetClientsOverviewAction()
    {
        $_REQUEST = [
            'objId' => '6B784CA159279518',
            'name' => 'hello',
            'ip' => '192.168.20.171',
            'mac' => '123-123-456',
            'sys' => '12',
            'os' => 'os',
            'vlibver' => '123.123',
            'filemon' => '1',
            'mailemon' => '1',
            'sysmon' => '1',
            'virusaction' => '1',
        ];

        $this->_eid = '6B784CA159279518';
        $this->groupid = '6B784CA159279518';
        $this->getClientsOverviewAction();
    }

    public function getXavStatisticsAction()
    {
        $model = new XAVLogModel($this->_eid);
        $data = $model->getXavStatistics($this->_eid);

        $this->ok($data);
    }
    #endregion

    #region 病毒查询统计

    /**
     * 病毒扫描结果查询、统计
     */
    public function getVirusListAction()
    {
        if (empty($this->input->viewtype)) {
            $this->notice('参数错误。', 1);
            return;
        }

        if (!isset($this->input->objtype)) {
            $this->notice('参数错误。', 1);
            return;
        }

        if (empty($this->input->objid)) {
            $this->notice('参数错误。', 1);
            return;
        }

        if (empty($this->input->paging)) {
            $this->notice('参数错误。', 1);
            return;
        }

        if (empty($this->input->queryconditions)) {
            $this->notice('参数错误。', 1);
            return;
        }

        if(empty($this->_eid)){
            $this->notice('登录过期。', 1);
            return;
        }
        $eid = $this->_eid;
        $data = null;
        $model = new XAVLogModel($eid);
        $viewType = $this->input->viewtype;

        if($viewType==='xav')
        {
            $data = $model->getXavByVirus($eid,$this->input->objtype,$this->input->objid,$this->input->queryconditions,$this->input->paging);
        }
        elseif($viewType==='ep')
        {
            $data = $model->getXavByEP($eid,$this->input->objtype,$this->input->objid,$this->input->queryconditions,$this->input->paging);
        }
        elseif($viewType==='detail')
        {
            $data = $model->getXavDetails($eid,$this->input->objtype,$this->input->objid,$this->input->queryconditions,$this->input->paging);
        }
        $this->ok($data);
    }

    public function testgetXavScanListAction()
    {
        $query = '{
    "objtype":"0:eid,1:groupid,2:sguid",
    "objid":"123",
    "viewtype": "detail",
    "timerange": {
        "begindate": "2016-6-1",
        "enddate": "2016-6-30"
    },
    "queryconditions": {
        "searchkey": "",
        "searchtype": "computername",
        "state": -1,
        "status": -2,
        "tablename": "",
        "taskname": "all",
        "groupguid": "911c9a2a-387"
    },
    "paging": {
        "orderfield": "computername",
        "ordertype": "0",
        "pageindex": 0,
        "pagesize": 10
    }
}';
        $_REQUEST = json_decode($query,true);
        $this->getXavScanListAction();
    }

    /**
     * 病毒扫描结果查询、统计
     */
    public function getVirusScanAction()
    {
        if (empty($this->input->viewtype)) {
            $this->notice('参数错误。', 1);
            return;
        }

        if (!isset($this->input->objtype)) {
            $this->notice('参数错误。', 1);
            return;
        }

        if (empty($this->input->objid)) {
            $this->notice('参数错误。', 1);
            return;
        }

        if (empty($this->input->paging)) {
            $this->notice('参数错误。', 1);
            return;
        }

        if (empty($this->input->queryconditions)) {
            $this->notice('参数错误。', 1);
            return;
        }

        if(empty($this->_eid)){
            $this->notice('登录过期。', 1);
            return;
        }
        $eid = $this->_eid;
        $data = null;
        $model = new XAVLogModel($eid);
        $viewType = $this->input->viewtype;

        if($viewType==='ep')
        {
            $data = $model->getXavScanDetails($eid,$this->input->objtype,$this->input->objid,$this->input->queryconditions,$this->input->paging);
        }

        $this->ok($data);
    }

    #endregion

    public function sysdefAction()
    {
        //for($i=0;$i<100;$i++){
            //MC::exec("insert into epinfo_E1D1D46572597546 values('E1D1D46572597546','E1D1D46572597546$i','osx','$i','$i','$i','$i','$i','$i','$i','$i.$i.$i.$i','$i','$i','$i','$i','$i','$i','2016-06-06','$i','$i','$i','2016-06-06','$i','2016-06-06','2016-06-06')");
        //}
        //for($i=0;$i<100;$i++){
            //MC::exec("insert into XAV_SysDef_E1D1D46572597546 values('2016-06-06','E1D1D46572597546','E1D1D46572597546$i','$i','2016-06-06','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i')");
        //}
        //$in = array(
            //'viewtype'=>'ep',//'xav'/'detail',
            //'objtype'=>0,//1/2,
            //'objid'=>'E1D1D46572597546',//'groupid'/'sguid',
            //'queryconditions' => array(
                //'status'=> 0,//1,
                //'searchtype'=> 'computername',//'ip',
                //'searchkey'=>'what',
                //'begintime'=>'2011-09-09 10:10:10',
                //'endtime'=>'2019-09-09 10:10:10',
                //'deftype'=> 1,//2/3/4,
            //),
            //'paging' => array(
                //'sort' => 'time',
                //'order' => 0,//1, // 0 asc, 1 desc
                //'offset' => 0,
                //'limit' => 10,
            //),
        //);
        //$this->input = json_decode(json_encode($in));
        // filter sguid

        $xav = new XAVLogModel($this->_eid);
        $sids = $xav->getSguids(
            $this->_eid,
            @$this->input->objtype ?: new stdclass,
            @$this->input->objid ?: new stdclass,
            @$this->input->queryconditions ?: new stdclass
        );
        if(!$sids){$this->ok([]);return;}
        $data = $xav->sysdef(
            $this->_eid,
            $sids,
            @$this->input->viewtype ?: 'ep',
            @$this->input->queryconditions ?: new stdclass,
            @$this->input->paging ?: new stdclass
        );
        $groupModel = new GroupModel();
        $data['rows'] = array_map(function($item)use($groupModel){
            $item['onlinestate'] = $groupModel->getClientOnlineState($item['eid'],$item['sguid'],$item['systype'],$item['unset']);
            return $item;
        }, $data['rows']);
        $this->ok($data);
    }

    public function botauditlogAction()
    {
        //for($i=0;$i<100;$i++){
            //MC::exec("insert into XAV_BoTAuditLog_E1D1D46572597546 values('2016-06-06','E1D1D46572597546','E1D1D46572597546$i','$i','2016-06-06','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i','$i')");
        //}
        //$in = array(
            //'viewtype'=>'ep'/'xav'/'detail',
            //'objtype'=>0/1/2,
            //'objid'=>'eid'/'groupid'/'sguid',
            //'queryconditions' => array(
                //'status'=> 0/1,
                //'searchtype'=> 'computername'/'ip',
                //'searchkey'=>'what',
                //'begintime'=>'2016-09-09 10:10:10',
                //'endtime'=>'2016-09-09 10:10:10',
                //'type'=> 0/1,
            //),
            //'paging' => array(
                //'sort' => 'time',
                //'order' => 0/1, // 0 asc, 1 desc
                //'offset' => 0,
                //'limit' => 10,
            //),
        //);
        //$this->input = json_decode(json_encode($in));
        // filter sguid

        $xav = new XAVLogModel($this->_eid);
        $sids = $xav->getSguids(
            $this->_eid,
            @$this->input->objtype ?: new stdclass,
            @$this->input->objid ?: new stdclass,
            @$this->input->queryconditions ?: new stdclass
        );
        if(!$sids){$this->ok([]);return;}
        $data = $xav->balog(
            $this->_eid,
            $sids,
            @$this->input->viewtype ?: 'ep',
            @$this->input->queryconditions ?: new stdclass,
            @$this->input->paging ?: new stdclass
        );
        $groupModel = new GroupModel();
        $data['rows'] = array_map(function($item)use($groupModel){
            $item['onlinestate'] = $groupModel->getClientOnlineState($item['eid'],$item['sguid'],$item['systype'],$item['unset']);
            return $item;
        }, $data['rows']);
        $this->ok($data);
    }

    public function getclientperilstatisticsAction()
    {
        $model = new XAVLogModel($this->_eid);
        $data = $model->getClientPerilStatistics($this->_eid);

        $this->ok($data);
    }

}
