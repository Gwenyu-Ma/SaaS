<?php
namespace Lib\Util;
use ChromePhp as Console;

class Doc
{
    public function __construct()
    {
    }

    public function save($sections, $name)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        foreach($sections as $v){
            $section = $phpWord->addSection();
            $s = explode("\n", $v);
            foreach($s as $vv){
                $section->addText($vv);
            }
        }
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $name = uniqid().'_'.$name;
        $objWriter->save(__DIR__ . "/../../file/$name.docx");
        return "/file/$name.docx";
    }

}

