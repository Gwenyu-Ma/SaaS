<?php
use Lib\Model\Group;
class GroupModel
{
    public function getGroups($eid)
    {
        return Group::getGroups($eid);
    }
}