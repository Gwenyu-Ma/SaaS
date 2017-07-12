<?php
class GroupController extends MyController
{
    public function testgetGroupsAction()
    {
        echo "组织结构接口:/group/getGroups</br>";      
        $_REQUEST=['eid'=>'D05D6DE488005623'];
        echo '参数:</br>';
        echo json_encode($_REQUEST);
        echo '</br>返回值:</br>';
        $this->getGroupsAction();
    }

    public function getGroupsAction()
    {
        $eid=$this->param('eid',null);
        $this->ok(GroupModel::getGroups($eid));
    }
}