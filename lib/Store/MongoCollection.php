<?php
// list methods : https://secure.php.net/manual/zh/class.mongocollection.php
namespace Lib\Store;

use \MongoCollection as Collection;

class MongoCollection
{
    protected $instance;
    protected $restrict = array('drop');

    const MUST_FIELD = 'eid';

    public function __construct(Collection $clct)
    {
        $this->instance = $clct;
    }

    public function __call($name, array $args)
    {
        if(in_array($name, $this->restrict)){
            throw new \Exception(__CLASS__ . ': Restricting Method');
        }
        if($name === 'insert'){
            $this->middle($args[0]);
        }
        if($name === 'remove'){
            $this->middle($args[0]);
        }
        if($name === 'update'){
            $this->middle($args[0]);
        }
        if($name === 'findAndModify'){
            $this->middle($args[0]);
        }
        if($name === 'save'){
            $this->middle($args[0]);
        }
        if($name === 'batchInsert'){
            foreach($args[0] as &$v){
                $this->middle($v);
            }
        }
        return call_user_func_array([$this->instance, $name], $args);
    }

    protected function middle(array &$data)
    {
        if(!empty($data['noeid'])){
            unset($data['noeid']);
            return;
        }
        if(!isset($data[self::MUST_FIELD]) || empty($data[self::MUST_FIELD])){
            throw new \Exception(__CLASS__ . ': Need eid');
        }
    }

}



