<?php
namespace Lib\Util\DT;

class DTList
{
    private $_array;
    function __construct($array)
    {
        $this->_array=$array;
    }

    public function select($cb)
    {
        $result=[];
        foreach($this->_array as $item){
            $itemNew=$cb($item);
            if(empty($itemNew)){
                continue;
            }
            array_push($result,$itemNew);
        }
        return $result;
    }
}
