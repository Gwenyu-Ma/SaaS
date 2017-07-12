<?php
namespace Lib\Model;

use Lib\Store\Mysql as M;
use \Lib\Store\MysqlWriteCluster as MW;

class Clean
{
    static public function user()
    {
        $rows = M::getAll('select EID as eid,CreateTime as ct from esm_user where Status=0');
        foreach($rows as $v){
            if(time() - strtotime($v['ct']) > 24*60*60){
                continue;
            }
            M::exec('delete from esm_user where EID=?', [$v['eid']]);
            //M::exec('delete from esm_organization where EID=?', [$v['eid']]);
        }
    }

    static public function cleanOrNot($uname)
    {
        $eid = M::getCell('select eid from esm_user where UserName=? and Status=0 limit 1', [$uname]);
        if(empty($eid)){
            return;
        }
        M::exec('delete from esm_user where EID=?', [$eid]);
        M::exec('delete from esm_organization where EID=?', [$eid]);
    }

    /*
    *func:注册激活失败后,删除创建的一些表和记录
     */
    static public function cleanRubbishInfo( $eid ){
        M::exec('DELETE FROM esm_user WHERE EID=?', [$eid]);
        /*M::exec('DELETE FROM esm_organization WHERE EID=?', [$eid]);

        MW::setEID($eid);
        MW::exec("CALL drop_eid_tables('$eid');");

        $objGroupInfo = select_manage_collection( 'groupinfo' );
        $objPolicyInfo = select_manage_collection( 'policyinfo' );
        $objAutoGroupInfo = select_manage_collection( 'autogroup' );
        $objGroupInfo->remove(array('eid' => $eid));
        $objPolicyInfo->remove(array('eid' => $eid));
        $objAutoGroupInfo->remove(array('eid' => $eid));*/
    }

    /*
     * func:通过eid删除esm_user中记录
     */
    public static function cleanEidInfo( $eid ){
        M::exec('DELETE FROM esm_user WHERE EID=?', [$eid]);
    }
}

