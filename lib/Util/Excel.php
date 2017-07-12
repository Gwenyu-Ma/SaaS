<?php
namespace Lib\Util;
use ChromePhp as Console;

class Excel
{

    public $maxRow = 10000;

    public function __construct($maxRow = 10000)
    {
        $this->maxRow = $maxRow;
    }

    public function save($rows, $name)
    {
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);

        $n = 0;
        for($y=0;$y<count($rows);$y++){
            if($y % $this->maxRow === 0 && $y !== 0){
                $objPHPExcel->createSheet();
                $objPHPExcel->setActiveSheetIndex($y/$this->maxRow);
                $n++;
            }
            $x = null;
            foreach($rows[$y] as $v){
                $x = $this->getNextX($x);
                $objPHPExcel->getActiveSheet()->setCellValue($x.($y-$n*$this->maxRow+1), $v);
            }
        }
        $name = uniqid().'_'.$name;
        $objWriter->save(__DIR__ . "/../../file/$name.xls");
        return "/file/$name.xls";
    }

    public function getNextX($x)
    {
        if(strlen($x) > 2){
            throw new \Exception('Only allow 2 letters');
        }
        if($x === null){
            return chr(65);
        }

        $a = ord($x[strlen($x)-1])+1;
        if($a <= 90){
            $x[strlen($x)-1] = chr($a);
            return $x;
        }

        if(strlen($x)===1){
            return chr(65) . chr(65);
        }

        $b = ord($x[strlen($x)-2])+1;
        if($b > 90){
            throw new \Exception('Only allow 2 letters');
        }
        return chr($b) . chr(65);
    }

}

