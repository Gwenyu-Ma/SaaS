<?php

use Lib\Store\MysqlCluster as MC;

class LogDataModel
{
    protected $eid;
    protected $sql;

    public function __construct($eid)
    {
        $this->eid = $eid;
        MC::$eid = $eid;
    }

    // * or array
    public function select($fields)
    {
        $this->sql .= "select";
        if(is_array($fields)){
            $this->sql .= sprintf(" %s", implode(',', $fields));
        }else{
            $this->sql .= sprintf(" %s", $fields);
        }
        return $this;
    }

    // string
    public function from($table)
    {
        $this->sql .= sprintf(" from %s_%s", $table, $this->eid);
        return $this;
    }

    // array ["name='hello'", "b>2", "(a=1 or a=2)"]
    public function where($wheres)
    {
        $this->sql .= " where";
        $this->sql .= sprintf(" %s", implode('and ', $wheres));
        return $this;
    }

    // string
    public function groupBy($field)
    {
        $this->sql .= sprintf(" group by %s", $field);
        return $this;
    }

    // array ["name='hello'", "b>2", "(a=1 or a=2)"]
    public function having($havings)
    {
        $this->sql .= " 1=1";
        $this->sql .= sprintf(" and %s", implode(',', $havings));
        return $this;
    }

    // string
    public function orderBy($field)
    {
        $this->sql .= sprintf(" order by %s", $field);
        return $this;
    }

    public function asc()
    {
        $this->sql .= " asc";
        return $this;
    }

    public function desc()
    {
        $this->sql .= " desc";
        return $this;
    }

    // int
    public function offset($offset)
    {
        $this->sql .= " limit $offset,";
        return $this;
    }

    // count
    public function limit($limit)
    {
        $this->sql .= $limit;
        return $this;
    }

    public function data()
    {
        MC::exec("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        return MC::getAll($this->sql);
    }
}


