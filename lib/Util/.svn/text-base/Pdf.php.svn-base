<?php
namespace Lib\Util;
use ChromePhp as Console;

class Pdf
{
    public function __construct()
    {
    }

    public function save($html, $name)
    {
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();
        $r = $dompdf->output();
        $name = uniqid().'_'.$name;
        file_put_contents(__DIR__ . "/../../file/$name.pdf", $r);
        return "/file/$name.pdf";
    }

}


