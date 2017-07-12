<?php
namespace Lib\Model;

class Group
{
    public static function getGroups($eid)
    {
        $data = [];
        $groupinfo = select_manage_collection('groupinfo');
        $itrt = $groupinfo->find(array('eid' => $eid),['_id'=>false]);
        $total = $itrt->count();
        if ($total === 0) {
            return null;
        }
        $data['total'] = $total;
        $data['rows']=iterator_to_array($itrt);
        return $data;
    }
}