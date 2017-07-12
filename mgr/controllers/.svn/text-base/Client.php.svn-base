<?php
use Lib\Util\DT\DTList;

class ClientController extends MyController
{
    public function initClientOSAction()
    {
        $eid=$this->param('eid',null);
        ClientModel::initClientOS($eid);
        $this->ok(null);
    }

    public function initClientLocAction()
    {
        //ClientModel::initClientLoc();
    }

    public function initClientWAction()
    {
        $eid=$this->param('eid',null);
        ClientModel::initClientW($eid);
        $this->ok(null);
    }

    public function testgetClientsAction()
    {
        echo "客户端管理页面接口:/client/getClients</br>";
        $_REQUEST=[
            'eid'=>'D05D6DE488005623',
            //'uName'=>'153',
            'sKey'=>'ip',
            'sValue'=>'193',
            'paging'=>[
                'order'=>0,
                'limit'=>10,
                'offset'=>10,
                'sort'=>'groupname'
            ]
        ];
        echo '参数:</br>';
        echo json_encode($_REQUEST);
        echo '</br>返回值:</br>';
        $this->getClientsAction();
    }

    public function getClientsAction()
    {
        $eid=$this->param('eid',null);
        $uName=$this->param('uName',null);
        $sKey=$this->param('sKey',null);
        $kValue=$this->param('sValue',null);
        $groupId=$this->param('groupId',null);
        $paging=$this->param('paging',['order'=>1,'limit'=>10,offset=>0,'sort'=>'OName']);
        $args=[
            'eid'=>$eid,
            'uName'=>$uName,
            'sKey'=>$sKey,
            'sValue'=>$kValue,
            'paging'=>$paging,
            'groupId'=>$groupId
        ];
        $result=ClientModel::getClientList($eid,$args,['eid','sguid','groupname','groupid','computername','memo','ip','mac','version','systype','edate','createtime']);
        $this->ok($result);
    }


    public function initEPPolicyAction()
    {
         $eid=$this->param('eid',null);
         $sguid=$this->param('sguid',null);
         ClientModel::initEPPolicy($eid,$sguid);
         $this->ok(null);
    }

    public function initEPInfoAction()
    {
         $eid=$this->param('eid',null);
         $groupId=$this->param('groupid',null);
         $sguid=$this->param('sguid',null);
         ClientModel::initEPInfo($eid,$sguid);
         $this->ok(null);
    }

    //测试每天新增客户端数
    public function testgetNewClientTrendAction()
    {
        echo '测试每天新增客户端数:/client/getNewClientTrend</br>';
        echo '参数:</ br>';
        echo "无";
        echo '</br>返回值:</br>';
        $this->ok([[
                time=>'2017-02-17',
                count=>2
        ],[
                time=>'2017-02-16',
                count=>2
        ],[
                time=>'2017-02-18',
                count=>2
        ],[
                time=>'2017-02-14',
                count=>2
        ],[
                time=>'2017-02-15',
                count=>2
        ],[
                time=>'2017-02-12',
                count=>2
        ]]);
    }

    //每天新增客户端数
    public function getNewClientTrendAction()
    {
        $result=ClientModel::getNewClientTrend(7);
        $this->ok($result);
    }

    //测试统计每分钟终端在线状态数量
    public function testgetClientOnLineStatByTimeAction()
    {
        echo '测试统计每分钟终端在线状态数量:/client/getClientOnLineStatByTime</br>';
        echo '参数:</ br>';
        echo "无";
        echo '</br>返回值:</br>';
        $this->ok([[
                time=>'2017-02-17',
                win=>6,
                android=>2,
                linux=>1
        ],[
                time=>'2017-02-18',
                win=>8,
                android=>1,
                linux=>3
        ],[
                time=>'2017-02-16',
                win=>3,
                android=>4,
                linux=>5
        ],[
                time=>'2017-02-15',
                win=>2,
                android=>1,
                linux=>2
        ],[
                time=>'2017-02-14',
                win=>4,
                android=>8,
                linux=>7
        ]]);
    }

    //统计每分钟终端在线状态数量
    public function getClientOnLineStatByTimeAction()
    {
        $result=ClientModel::getClientOnLineStatByTime();
        $list=new DTList($result);
        $result=$list->select(function($item){
            return [
                'time'=>$item['counttime'],
                'win'=>$item['winnum'],
                'android'=>$item['androidnum'],
                'linux'=>$item['linuxnum']
            ];
        });

        $this->ok($result);
    }

    //测试按操作系统统计客户端个数
    public function testgetClientOSTypeStatAction()
    {
        echo '测试按操作系统统计客户端个数:/client/getClientOSTypeStat</br>';
        echo '参数:</ br>';
        echo "无";
        echo '</br>返回值:</br>';
        $this->ok([
                win=>1,
                android=>1,
                linux=>1,
                ucount=>100,
                ecount=>20
        ]);
    }
    //按操作系统统计客户端个数
    public function getClientOSTypeStatAction()
    {
        $this->ok(ClientModel::getClientOSTypeStat());
    }
}