<?php
use \Lib\Store\MysqlCluster as MC;
use \Lib\Model\RedisDataManager;

/**
 *
 */
class PhoneLogModel
{

    public function __construct()
    {
        # code...
    }

    public function getClientsOverview($eid, $argArr, $columns)
    {
        $groupId = $argArr['objId'];
        $clientWhere = [];
        if (!empty($argArr['name'])) {
            $clientWhere['name'] = $argArr['name'];
        }
        if (!empty($argArr['ip'])) {
            $clientWhere['ip'] = $argArr['ip'];
        }
        if (!empty($argArr['mac'])) {
            $clientWhere['mac'] = $argArr['mac'];
        }
        if (!empty($argArr['sys'])) {
            $clientWhere['sys'] = $argArr['sys'];
        }
        if (!empty($argArr['os'])) {
            $clientWhere['os'] = $argArr['os'];
        }
        if (!empty($argArr['productIds'])) {
            $clientWhere['productIds'] = $argArr['productIds'];
        }
        $clientWhere['onlinestate']=$argArr['onlinestate'];
        $epWhere = [];

        if (strlen($argArr['vlibver'])>0) {
            $preg = "/" . $argArr['vlibver'] . "/i";
            $regexObj = new MongoRegex($preg);
            $epWhere['andvirusdbversion'] = $regexObj;

        }

        if (intval($argArr['loc']) >= 0) {

            $epWhere['andloc'] = strval($argArr['loc']);
        }

        if (intval($argArr['spam']) >= 0) {

            $epWhere['andspam'] = strval($argArr['spam']);
        }

        if (intval($argArr['time_spam']) >= 0) {
            $epWhere['andtimespam'] = strval($argArr['time_spam']);
        }

        $group = new GroupModel();
        return $clientStateArr = $group->getComputersEpState($eid, $groupId, $clientWhere, $epWhere, $columns);
    }
    public function getSpamMsgLog($eid, $objtype, $objId, $params, $paging)
    {
        $xavModel = new XAVLogModel($eid);
        $sids = $xavModel->getSguids($eid, $objtype, $objId, $params);
        //var_dump([$eid, $objtype, $objId, $params]);
        if (!$sids) {
            return null;
        }
        $where = sprintf(" and ep.sguid in ('%s')", implode("','", $sids));
        $sqlParams = [];
        if (!empty($params->begintime)) {
            $where .= " and date >= ?";
            array_push($sqlParams, $params->begintime);
        }
        if (!empty($params->endtime)) {
            $where .= " and date <= ? ";
            array_push($sqlParams, $params->endtime);
        }
        if(!empty($params->searchkey)&&!empty($params->searchtype)&&$params->searchtype=='content'){
            $where .= ' and content like ?';
            $sqlParams[] = '%'.$params->searchkey.'%';
        }
        if(!empty($params->searchkey)&&!empty($params->searchtype)&&$params->searchtype=='phone'){
            $where .= ' and phone like ?';
            $sqlParams[] = '%'.$params->searchkey.'%';
        }
        if(!empty($params->searchkey)&&!empty($params->searchtype)&&$params->searchtype=='reason'){
            $where .= ' and reason like ?';
            $sqlParams[] = '%'.$params->searchkey.'%';
        }
        // order
        $order = !empty($paging->sort) ? $paging->sort : 'date';
        $sort = isset($paging->order) && $paging->order === 0 ? 'asc' : 'desc';
        $offset = isset($paging->offset) ? $paging->offset : 0;
        $limit = isset($paging->limit) ? $paging->limit : 10;
        $sql = <<<SQL
SELECT count(1) as count
FROM  AND_Phone_SpamMsg_%s ep where 1=1 %s
SQL;

        $sql = sprintf($sql,
            $eid,
            $where
        );
        //echo $sql;
        $total = MC::getCell($sql, $sqlParams);
        $rows = [];
        if ($total > 0) {
            $sql = <<<SQL
SELECT ep.eid,ep.sguid,computername,ip,memo,ep.systype systype,content,phone,unset,date,reason
FROM epinfo_%s ep
INNER JOIN
AND_Phone_SpamMsg_%s p
ON ep.sguid=p.sguid where 1=1 %s
order by %s %s limit %s,%s
SQL;
            $sql = sprintf($sql,
                $eid,
                $eid,
                $where,
                $order,
                $sort,
                $offset,
                $limit
            );

            $rows = MC::getAll($sql, $sqlParams);

            $rows = array_map(function ($item) {
                $groupModel = new GroupModel();
                $item['onlinestate'] = $groupModel->getClientOnlineState($item['eid'], $item['sguid'], $item['systype'], $item['unset']);
                return $item;
            }, $rows);
        }

        $data = array(
            'rows' => $rows,
            'total' => $total,
        );
        return $data;
    }

    public function getSpamPhoneLog($eid, $objtype, $objId, $params, $paging)
    {
        $xavModel = new XAVLogModel($eid);
        $sids = $xavModel->getSguids($eid, $objtype, $objId, $params);
        //var_dump($sids);
        if (!$sids) {
            return null;
        }
        $where = sprintf(" and ep.sguid in ('%s')", implode("','", $sids));
        $sqlParams = [];
        if (!empty($params->begintime)) {
            $where .= " and date >= ?";
            array_push($sqlParams, $params->begintime);
        }
        if (!empty($params->endtime)) {
            $where .= " and date <= ? ";
            array_push($sqlParams, $params->endtime);
        }

        if(!empty($params->searchkey)&&!empty($params->searchtype)&&$params->searchtype=='phone'){
            $where .= ' and phone like ?';
            $sqlParams[] = '%'.$params->searchkey.'%';
        }
        if(!empty($params->searchkey)&&!empty($params->searchtype)&&$params->searchtype=='reason'){
            $where .= ' and reason like ?';
            $sqlParams[] = '%'.$params->searchkey.'%';        }

        // order
        $order = !empty($paging->sort) ? $paging->sort : 'date';
        $sort = isset($paging->order) && $paging->order === 0 ? 'asc' : 'desc';
        $offset = isset($paging->offset) ? $paging->offset : 0;
        $limit = isset($paging->limit) ? $paging->limit : 10;
        $sql = <<<SQL
SELECT count(1) as count
FROM  AND_Phone_SpamPhone_%s ep where 1=1 %s
SQL;

        $sql = sprintf($sql,
            $eid,
            $where
        );

        $total = MC::getCell($sql, $sqlParams);
        $rows = [];
        if ($total > 0) {
            $sql = <<<SQL
SELECT ep.eid,ep.sguid,computername,ip,memo,ep.systype systype,phone,unset,date,reason
FROM epinfo_%s ep
INNER JOIN
AND_Phone_SpamPhone_%s p
ON ep.sguid=p.sguid where 1=1 %s
order by %s %s limit %s,%s
SQL;
            $sql = sprintf($sql,
                $eid,
                $eid,
                $where,
                $order,
                $sort,
                $offset,
                $limit
            );

            $rows = MC::getAll($sql, $sqlParams);

            $rows = array_map(function ($item) {
                $groupModel = new GroupModel();
                $item['onlinestate'] = $groupModel->getClientOnlineState($item['eid'], $item['sguid'], $item['systype'], $item['unset']);
                return $item;
            }, $rows);
        }

        $data = array(
            'rows' => $rows,
            'total' => $total,
        );
        return $data;
    }

    public function getScanEventLog($eid, $objtype, $objId, $params, $paging)
    {

        //查询条件：时间范围、状态、（终端名称、IP地址）搜索筛选
        $xavModel = new XAVLogModel($eid);
        $sids = $xavModel->getSguids($eid, $objtype, $objId, $params);
        //var_dump($sids);
        if (!$sids) {
            return null;
        }
        $where = sprintf(" and ep.sguid in ('%s')", implode("','", $sids));
        $sqlParams = [];
        if (!empty($params->begintime)) {
            $where .= " and starttime >= ?";
            array_push($sqlParams, $params->begintime);
        }
        if (!empty($params->endtime)) {
            $where .= " and starttime <= ? ";
            array_push($sqlParams, $params->endtime);
        }
        if (isset($params->state) && $params->state != -1) {
            $where .= ' and state=?';
            $sqlParams[] = $params->state;
        }

        // order
        $order = !empty($paging->sort) ? $paging->sort : 'date';
        $sort = isset($paging->order) && $paging->order === 0 ? 'asc' : 'desc';
        $offset = isset($paging->offset) ? $paging->offset : 0;
        $limit = isset($paging->limit) ? $paging->limit : 10;
        $sql = <<<SQL
SELECT count(1) as count
FROM  AND_ScanEvent_%s ep where 1=1 %s
SQL;

        $sql = sprintf($sql,
            $eid,
            $where
        );

        $total = MC::getCell($sql, $sqlParams);
        $rows = [];
        if ($total > 0) {
            //终端名称、IP地址、开始时间、状态、共扫描、发现威胁、已处理、扫描用时
            $sql = <<<SQL
SELECT ep.eid,ep.sguid,computername,ip,memo,ep.systype systype,starttime,unset,state,scanCount,virusCount,treatedCount,runtime
FROM epinfo_%s ep
INNER JOIN
AND_ScanEvent_%s p
ON ep.sguid=p.sguid where 1=1 %s
order by %s %s limit %s,%s
SQL;
            $sql = sprintf($sql,
                $eid,
                $eid,
                $where,
                $order,
                $sort,
                $offset,
                $limit
            );

            $rows = MC::getAll($sql, $sqlParams);

            $rows = array_map(function ($item) {
                $groupModel = new GroupModel();
                $item['onlinestate'] = $groupModel->getClientOnlineState($item['eid'], $item['sguid'], $item['systype'], $item['unset']);
                return $item;
            }, $rows);
        }

        $data = array(
            'rows' => $rows,
            'total' => $total,
        );
        return $data;
    }

    public function getVirusByVirus($eid, $objtype, $objId, $params, $paging)
    {
        //查询条件：时间范围、威胁类型、状态、（威胁名、文路径、终端名称、IP地址）搜索筛选
        $xavModel = new XAVLogModel($eid);
        $sids = $xavModel->getSguids($eid, $objtype, $objId, $params);
        //var_dump($sids);
        if (!$sids) {
            return null;
        }
        $where = sprintf(" and sguid in ('%s')", implode("','", $sids));
        $sqlParams = [];
        if (!empty($params->begintime)) {
            $where .= " and findtime >= ?";
            array_push($sqlParams, $params->begintime);
        }
        if (!empty($params->endtime)) {
            $where .= " and findtime <= ? ";
            array_push($sqlParams, $params->endtime);
        }
        if (isset($params->virusclass) && $params->virusclass != -1) {
            $where .= ' and virusclass=?';
            $sqlParams[] = $params->virusclass;
        }
        if (isset($params->state) && $params->state != -1) {
            $where .= ' and state=?';
            $sqlParams[] = $params->state;
        }
        if(!empty($params->searchkey)&&!empty($params->searchtype)&&$params->searchtype=='virusname'){
            $where .= ' and virusname like ?';
            $sqlParams[] = '%'.$params->searchkey.'%';
        }

        // order
        $order = !empty($paging->sort) ? $paging->sort : 'date';
        $sort = isset($paging->order) && $paging->order === 0 ? 'asc' : 'desc';
        $offset = isset($paging->offset) ? $paging->offset : 0;
        $limit = isset($paging->limit) ? $paging->limit : 10;
        $sql = <<<SQL
SELECT count(1) as count
FROM  AND_Virus_%s ep where 1=1 %s group by virusname
SQL;

        $sql = sprintf($sql,
            $eid,
            $where
        );

        $total = MC::getCell($sql, $sqlParams);
        $rows = [];
        if ($total > 0) {
            //时间、终端名称、IP地址、文件路径、威胁类型、威胁名、状态

            $sql = <<<SQL
SELECT MAX(virusclass) virusclass,virusname,count(DISTINCT sguid) virusCount
FROM AND_Virus_%s
where 1=1 %s
order by %s %s limit %s,%s
SQL;
            $sql = sprintf($sql,
                $eid,
                $where,
                $order,
                $sort,
                $offset,
                $limit
            );

            $rows = MC::getAll($sql, $sqlParams);
        }

        $data = array(
            'rows' => $rows,
            'total' => $total,
        );
        return $data;
    }

    public function getVirusLog($eid, $objtype, $objId, $params, $paging)
    {
        //查询条件：时间范围、威胁类型、状态、（威胁名、文路径、终端名称、IP地址）搜索筛选
        $xavModel = new XAVLogModel($eid);
        $sids = $xavModel->getSguids($eid, $objtype, $objId, $params);
        //var_dump($sids);
        if (!$sids) {
            return null;
        }
        $where = sprintf(" and ep.sguid in ('%s')", implode("','", $sids));
        $sqlParams = [];
        if (!empty($params->begintime)) {
            $where .= " and findtime >= ?";
            array_push($sqlParams, $params->begintime);
        }
        if (!empty($params->endtime)) {
            $where .= " and findtime <= ? ";
            array_push($sqlParams, $params->endtime);
        }
        if (isset($params->virusclass) && $params->virusclass != -1) {
            $where .= ' and virusclass=?';
            $sqlParams[] = $params->virusclass;
        }
        if (isset($params->state) && $params->state != -1) {
            $where .= ' and state=?';
            $sqlParams[] = $params->state;
        }
        if(!empty($params->searchkey)&&!empty($params->searchtype)&&$params->searchtype=='virusname'){
            $where .= ' and virusname like ?';
            $sqlParams[] = '%'.$params->searchkey.'%';
        }
        if(!empty($params->searchkey)&&!empty($params->searchtype)&&$params->searchtype=='filepath'){
            $where .= ' and filepath like ?';
            $sqlParams[] = '%'.$params->searchkey.'%';
        }
        // order
        $order = !empty($paging->sort) ? $paging->sort : 'date';
        $sort = isset($paging->order) && $paging->order === 0 ? 'asc' : 'desc';
        $offset = isset($paging->offset) ? $paging->offset : 0;
        $limit = isset($paging->limit) ? $paging->limit : 10;
        $sql = <<<SQL
SELECT count(1) as count
FROM  AND_Virus_%s ep where 1=1 %s
SQL;

        $sql = sprintf($sql,
            $eid,
            $where
        );

        $total = MC::getCell($sql, $sqlParams);
        $rows = [];
        if ($total > 0) {
            //时间、终端名称、IP地址、文件路径、威胁类型、威胁名、状态

            $sql = <<<SQL
SELECT ep.eid,ep.sguid,computername,ip,memo,ep.systype systype,unset,filepath,virusclass,virusname,state,findtime
FROM epinfo_%s ep
INNER JOIN
AND_Virus_%s p
ON ep.sguid=p.sguid where 1=1 %s
order by %s %s limit %s,%s
SQL;
            $sql = sprintf($sql,
                $eid,
                $eid,
                $where,
                $order,
                $sort,
                $offset,
                $limit
            );

            $rows = MC::getAll($sql, $sqlParams);

            $rows = array_map(function ($item) {
                $groupModel = new GroupModel();
                $item['onlinestate'] = $groupModel->getClientOnlineState($item['eid'], $item['sguid'], $item['systype'], $item['unset']);
                return $item;
            }, $rows);
        }

        $data = array(
            'rows' => $rows,
            'total' => $total,
        );
        return $data;
    }

    public function getPhoneSpam($eid)
    {
        if (empty($eid)) {
            return null;
        }

        $result=[
            'hp'=>[],
            'hm'=>[]
        ];
        $days=6;
        while ($days >= 0) {
            $date=date('Ymd',strtotime(-$days.' day'));
            foreach ($result as $key => $value) {
                $result[$key][date('Y-m-d',strtotime(-$days.' day'))]=RedisDataManager::getRfwValue($key,$eid,$date);
            }
            $days--;
        }
        return $result;
    }

    public function getPhonelocal($eid, $objtype, $objId, $params)
    {
        if (empty($eid)) {
            return null;
        }
        //查询条件：时间范围、状态、（终端名称、IP地址）搜索筛选
        $xavModel = new XAVLogModel($eid);
        $sids = $xavModel->getSguids($eid, $objtype, $objId, $params);
        //var_dump($sids);
        if (!$sids) {
            return null;
        }
        $where = sprintf(" and ep.sguid in ('%s')", implode("','", $sids));
        $sqlParams = [];
        if (!empty($params->begintime)) {
            $where .= " and date >= ?";
            array_push($sqlParams, $params->begintime);
        }
        if (!empty($params->endtime)) {
            $where .= " and date <= ? ";
            array_push($sqlParams, $params->endtime);
        }

        $sql = <<<SQL
SELECT lc.eid,lc.sguid,ep.computername ,ep.memo,ep.ip,ep.systype, lng,lat,lc.date  FROM AND_Loc_%s lc
INNER JOIN epinfo_%s ep
ON lc.eid = ep.eid AND lc.sguid = ep.sguid
where 1=1 %s ORDER BY ep.sguid, date DESC
SQL;

        $sql = sprintf($sql,
            $eid,
            $eid,
            $where
        );

        $rows = MC::getAll($sql, $sqlParams);
        $rows = $xavModel->setOnlinestate($eid,$rows);
        $rows = $this->mergePhonelocal($rows);

        $data = array(
            'rows' => $rows
        );
        return $data;
    }

    private function mergePhonelocal($data)
    {
        if(empty(data))
        {
            return null;
        }

        $rows = array();
        $i=0;
        $tmp = array();
        foreach($data as $row){
            if(!empty($tmp)&& $tmp['sguid']==$row['sguid']){
                array_push($tmp['path'], array($row['lng'],$row['lat'],$row['date']));
            }
            elseif($i==0||(!empty($tmp)&& $tmp['sguid']!=$row['sguid'])){
                if($i>0){
                    array_push($rows,$tmp);
                    $tmp = array();
                }

                $tmp['sguid'] = $row['sguid'];
                $tmp['name'] = $row['computername'];
                //$tmp['date'] = date('Y-m-d',strtotime($row['date']));
                $tmp['date'] = $row['date'];
                $tmp['online']=$row['onlinestate'];
                $tmp['ip'] = $row['ip'];
                $tmp['path'] = array();
                array_push($tmp['path'], array($row['lng'],$row['lat'],$row['date']));
            }
            $i = $i+1;
        }
        if(!empty($tmp) ){
            array_push($rows,$tmp);
        }
        return $rows;
    }

}
