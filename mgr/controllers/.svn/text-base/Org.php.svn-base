<?php
use Lib\Util\DT\DTList;

class OrgController extends MyController
{
    public function testgetOrgsAction()
    {
        echo '组织管理页面接口:/org/getOrgs</br>';
        $_REQUEST=[
            'eid'=>'',
            'oName'=>'',
            'uName'=>'',
            'paging'=>[
                'order'=>1,
                'limit'=>30,
                'offset'=>0,
                'sort'=>''
            ]
        ];
        echo '参数:</ br>';
        echo json_encode($_REQUEST);
        echo '</br>返回值:</br>';
        $this->getOrgsAction();
    }

    public function testgetOrgAction()
    {
        echo '组织管理页面接口:/org/getOrg</br>';
        $_REQUEST=[
            'eid'=>'D05D6DE488005623',
        ];
        echo '参数:</ br>';
        echo json_encode($_REQUEST);
        echo '</br>返回值:</br>';
        $this->getOrgAction();
    }

    public function getOrgAction()
    {
        $eid=$this->param('eid',null);
        $result=EnterpriseModel::getOrgs(['eid'=>$eid]);
        $this->ok(array_shift($result['rows']));
    }

    public function getOrgsAction()
    {
        $eid=$this->param('eid',null);
        $oName=$this->param('oName',null);
        $uName=$this->param('uName',null);
        $paging=$this->param('paging',['order'=>1,'limit'=>10,'offset'=>0,'sort'=>'OName']);

        $args=[
            'eid'=>$eid,
            'oName'=>$oName,
            'uName'=>$uName,
            'paging'=>$paging
        ];

        $result=EnterpriseModel::getOrgs($args);
        $this->ok($result);
    }

    public function testgetLogoAction()
    {
        $_REQUEST=['eid'=>'D05D6DE488005623'];
        $this->getLogoAction();
    }

    public function getLogoAction()
    {
        $eid=$this->param('eid',null);
        echo EnterpriseModel::getLogo($eid);
    }

    public function testusedspaceAction()
    {
        echo '组织管理页面接口:/org/usedspace</br>';
        $_REQUEST=['eid'=>'D05D6DE488005623'];
        echo '参数:</ br>';
        echo json_encode($_REQUEST);
        echo '</br>返回值:</br>';
        $this->usedspaceAction();
    }
    /**
     * 云中心存储信息概况
     *
     * @return json
     */
    public function usedspaceAction()
    {
        $eid=$this->param('eid',null);
        $data = EnterpriseModel::usedSpace($eid);
        $this->ok($data, '成功');

    }

    //获取企业eid数量
    public function getCountByEIDAction()
    {
        $result=EnterpriseModel::getCountByEID();
        $this->ok($result);
    }

    //获取新增企业列表
    public function getNewOrgsAction()
    {
        $result=EnterpriseModel::getNewOrgs();
        $this->ok($result);
    }

    //测试每天新增企业列表
    public function testgetNewOrgTrendAction()
    {
        echo '(文档有问题)新增企业列表:/org/getNewOrgTrend</br>';

        echo '参数:</ br>';
        echo "无";
        echo '</br>返回值:</br>';
        $this->ok([[
            time=>'2017-2-14',
            ucount=>1,
            ecount=>1
        ],[
            time=>'2017-2-15',
            ucount=>1,
            ecount=>1
        ],[
            time=>'2017-2-16',
            ucount=>1,
            ecount=>1
        ],[
            time=>'2017-2-18',
            ucount=>1,
            ecount=>1
        ],[
            time=>'2017-2-19',
            ucount=>1,
            ecount=>1
        ]]);
    }

    //获取每天新增企业趋势
    public function getNewOrgTrendAction()
    {
        $top=$this->param("top",7);
        $result=EnterpriseModel::getNewOrgTrend(intval($top));
        $this->ok($result);
    }

    //测试企业大小统计接口
    public function testgetOrgSizeStatAction()
    {
        echo '测试企业大小统计接口:/org/getOrgSizeStat</br>';

        echo '参数:</ br>';
        echo "无";
        echo '</br>返回值:</br>';
        $this->ok([[
            eid=>'eid1',
            count=>1
        ],[
            eid=>'eid2',
            count=>2
        ]]);
    }
    //企业大小统计
    public function getOrgSizeStatAction()
    {
        $result=EnterpriseModel::getOrgSizeStat(7);
        $list=new DTList($result);
        $result=$list->select(function($item){
            return [
                'eid'=>$item['eid'],
                'oname'=>$item['OName'],
                'osize'=>$item['OSize'],
                'count'=>$item['sguidnum'],
                'createtime'=>$item['Time']
            ];
        });
        $this->ok($result);
    }
}