<?php
/**
 * @file AutoGroup.php
 * @brief auto into group logic
 * @author cloud@txthinking.com
 * @version 0.0.1
 * @date 2016-03-08
 */
namespace Lib\Model;

use Lib\Util\Log;
use Respect\Validation\Validator as v;
use Lib\Store\RedisCluster as Redis;
use ChromePhp as Console;
use \Lib\Store\RdKFK;
use Lib\Util\Common as UCommon;

class AutoGroup
{
    public function __construct()
    {
        $this->autogroup = select_manage_collection('autogroup');
        $this->groupinfo = select_manage_collection('groupinfo');
        $this->epinfo = select_manage_collection('epinfo');
        $this->policyinfo = select_manage_collection('policyinfo');
    }

    /*
     * 策略结构
     * type         symbol      value
     *
     * ip           equal       x
     * ip           notequal    x
     * ip           in          x-y
     * ip           notin       x-y
     *
     * os           has         x
     * os           nothas      x
     *
     * computername has         x
     * computername nothas      x
     *
     * return bool | string(error)
     */
    public function checkRule($rule)
    {
        // check policies whether valid
        $ok = v::type('array')->validate($rule);
        if(!$ok){
            return '规则格式错误';
        }
        foreach($rule as &$v){
            $ok = v::keySet(
                v::key('type', v::type('string')),
                v::key('symbol', v::type('string')),
                v::key('value', v::type('string'))
            )->validate($v);
            if(!$ok){
                return '规则格式错误';
            }

            if($v['type'] === 'ip'){
                if(!in_array($v['symbol'], ['equal', 'notequal', 'in', 'notin'])){
                    return "错误的IP运算符: ".$v['symbol'];
                }
                if(in_array($v['symbol'], ['equal', 'notequal'])){
                    if(!v::ip()->validate($v['value'])){
                        return "IP格式不正确: ".$v['value'];
                    }
                }
                if(in_array($v['symbol'], ['in', 'notin'])){
                    $ips = explode('-', $v['value']);
                    if(count($ips) !== 2 || !v::ip()->validate($ips[0]) || !v::ip()->validate($ips[1])){
                        return "IP区间格式不正确: ".$v['value'];
                    }
                }
                continue;
            }

            if($v['type'] === 'os'){
                if(!in_array($v['symbol'], ['has', 'nothas'])){
                    return "错误的OS运算符: ".$v['symbol'];
                }
                continue;
            }

            if($v['type'] === 'computername'){
                if(!in_array($v['symbol'], ['has', 'nothas'])){
                    return "错误的OS运算符: ".$v['symbol'];
                }
                continue;
            }
            return "未知的规则类型: ".$v['type'];
        }
        return true;
    }

    /**
     * @brief updateRule
     *
     * @param $eid
     * @param $more
     *        [[ groupid => xx, rule => xx ]...]
     *
     * @return
     */
    public function updateRules($eid, $rules)
    {
        $ok = v::type('array')->validate($rules);
        if(!$ok){
            return '参数格式错误';
        }
        foreach($rules as $v){
            $ok = v::keySet(
                v::key('groupid', v::type('int')),
                v::key('rule', v::type('array'))
            )->validate($v);
            if(!$ok){
                return '参数格式错误';
            }
            $ok = $this->checkRule($v['rule']);
            if(is_string($ok)){
                return $ok;
            }
        }
        $doc = $this->autogroup->findOne([
            'eid' => $eid,
        ], ['eid'=>true]);

        if(!$doc){
            // insert
            $ok = $this->autogroup->insert([
                'eid' => $eid,
                'rules' => $rules,
                'edate' => time(),
            ]);
            if(empty($ok) || !empty($ok['err'])){
                Log::add("插入失败", $ok);
                return '插入失败';
            }
            return true;
        }
        // update
        $ok = $this->autogroup->update($doc, ['$set' => [
            'rules' => $rules,
        ]]);
        if(empty($ok) || !empty($ok['err'])){
            Log::add("更新失败", $ok);
            return '更新失败';
        }
        return true;
    }

    /**
     * @brief match
     *
     * @param $ep
     * @param $rule
     *
     * @return return groupid | null
     */
    protected function match($ep, $rule)
    {
        foreach($rule['rule'] as $v){
            if($v['type'] === 'ip'){
                if($ep['ip'] === null){
                    return null;
                }
                switch($v['symbol']){
                case 'equal':
                    if($v['value'] !== $ep['ip']){
                        return null;
                    }
                    break;
                case 'notequal':
                    if($v['value'] === $ep['ip']){
                        return null;
                    }
                    break;
                case 'in':
                    $tmp = explode('-', $v['value']);
                    $left = sprintf("%u", ip2long($tmp[0]));
                    $right = sprintf("%u", ip2long($tmp[1]));
                    $n = sprintf("%u", ip2long($ep['ip']));
                    if($n<$left || $n>$right){
                        return null;
                    }
                    break;
                case 'notin':
                    $tmp = explode('-', $v['value']);
                    $left = sprintf("%u", ip2long($tmp[0]));
                    $right = sprintf("%u", ip2long($tmp[1]));
                    $n = sprintf("%u", ip2long($ep['ip']));
                    if($n>=$left && $n<=$right){
                        return null;
                    }
                    break;
                }
                continue;
            } // end if

            if($v['type'] === 'os'){
                switch($v['symbol']){
                case 'has':
                    if(stripos($ep['os'], $v['value']) === false){
                        return null;
                    }
                    break;
                case 'nothas':
                    if(stripos($ep['os'], $v['value']) !== false){
                        return null;
                    }
                    break;
                }
                continue;
            }// end if

            if($v['type'] === 'computername'){
                switch($v['symbol']){
                case 'has':
                    if(stripos($ep['computername'], $v['value']) === false){
                        return null;
                    }
                    break;
                case 'nothas':
                    if(stripos($ep['computername'], $v['value']) !== false){
                        return null;
                    }
                    break;
                }
                continue;
            }// end if
        } // end foreach
        return $rule['groupid'];
    }

    /**
     * @brief autoInGroup
     *
     * @param $eid
     *
     * @return groupid | string (error)
     */
    public function autoInGroup($eid, $sguid)
    {
        $ep = $this->epinfo->findOne([
            'eid' => $eid,
            'sguid' => $sguid,
        ], ['eid'=>true, 'sguid'=>true, 'ip'=>true, 'os'=>true, 'computername'=>true]);
        if(!$ep){
            return '终端不存在';
        }

        // match
        $grpid = null;
        $row = $this->autogroup->findOne([
            'eid' => $eid,
        ], ['eid'=>true, 'rules'=>true]);
        if($row !== null){
            if(!v::ip()->validate($ep['ip'])){
                $ep['ip'] = null;
            }
            foreach($row['rules'] as $v){
                $grpid = $this->match($ep, $v);
                if(is_int($grpid)){
                    break;
                }
            }
        }
        // is deleted?
        $row = $this->groupinfo->findOne([
            'eid' => $eid,
            'id' => $grpid,
        ]);
        if(!$row){
            $grpid = null;
        }
        // default
        if($grpid === null){
            $row = $this->groupinfo->findOne([
                'eid' => $eid,
                'type' => 0,
            ], ['eid'=>true, 'id'=>true]);
            if($row === null){
                return "没有默认分组";
            }
            $grpid = $row['id'];
        }

        // update
        $row = $this->groupinfo->findOne([
            'id' => $grpid,
        ], ['groupname'=>true]);
        $set = [
            'groupid' => $grpid,
            'groupname' => $row['groupname'],
            'joingroupdate' => time(),
        ];
        $policy = $this->policyinfo->findOne([
            'eid' => $eid,
            'grouptype' => 1,
            'policyobject' => $grpid,
        ], ['policyver'=>true, 'policyjson'=>true,]);
        if($policy){
            $set['pstamp'] = $policy['policyver'];
        }
        $ok = $this->epinfo->update(['_id'=>$ep['_id'], 'eid'=>$ep['eid']], ['$set' => $set]);
        if(empty($ok) || !empty($ok['err'])){
            Log::add('ERROR_AUTO_GROUP', array(
                'string' => sprintf("ep: %s更新组%s失败", $ep['_id'], $grpid),
                'result' => $ok,
            ));
            return '更新组失败';
        }
        add_oplog('执行','2008',null,null,null,0, '执行重新入组成功');
        Log::add("autoingroup", ["sguid"=>$sguid, "groupid"=>$grpid]);
       RedisDataManager::initEPInfo($eid, $grpid, $sguid);

       UCommon::writeKafka($eid,[
            'eid'=>$eid,
            'sguid'=>$sguid,
            'logtype'=>'epinfo',
            'optype'=>'u',
            'groupid' => $set['groupid'],
            'groupname' => $set['groupname'],
            'joingroupdate' => strval($set['joingroupdate'])
        ]);

        Log::add($eid,['eid'=>$eid, 'sguid'=>$sguid]);
        return $grpid;
    }

    public function makeGroupAutoInGroup($eid, $groupid)
    {
        $rows = $this->epinfo->find(['eid'=>$eid, 'groupid'=>$groupid], ['sguid'=>true]);
        foreach($rows as $v){
            $grpid = $this->autoInGroup($eid, $v['sguid']);
            if(!is_int($grpid)){
                return $grpid;
            }
        }
        return true;
    }

    public function makeAllAutoInGroup($eid)
    {
        $rows = $this->epinfo->find(['eid'=>$eid], ['sguid'=>true]);
        foreach($rows as $v){
            $grpid = $this->autoInGroup($eid, $v['sguid']);
            if(!is_int($grpid)){
                return $grpid;
            }
        }
        return true;
    }

    public function getRules($eid)
    {
        $doc = $this->autogroup->findOne([
            'eid' => $eid,
        ], ['rules'=>true]);
        if(!is_array($doc)){
            return null;
        }
        return $doc['rules'];
    }

}

