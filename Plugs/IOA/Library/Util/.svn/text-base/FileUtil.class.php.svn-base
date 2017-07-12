<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-09-07
 * Time: 20:04
 */

namespace Plugs\IOA\Library\Util;

/**
 * 文件操作工具类
 * Class FileUtil
 * @package Common\Util
 */
class FileUtil {
    private $fileUrl;
    private $filePath;
    private $fileName;

    public function __construct($fileUrl){
        $this->fileUrl  = $fileUrl;

        /*解析文件路径和文件名*/
        $this->parseFileInfo();

        /*若目录不存在，则创建目录，支持多级目录*/
        $this->mkdir();
    }

    /**
     * 实现从文件中读取内容
     */
    public function readLine(){
        $fileObject = fopen($this->fileUrl, "r");

        while (!feof($fileObject)) {
            $buffer = fgets($fileObject, 4096);

            $fileObject.fclose();

            return $buffer;
        }
    }

    /**
     * 实现向文件中写入内容
     * @param $content 写入内容
     */
    public function write($content){
        $content    = $content.PHP_EOL;
        file_put_contents($this->fileUrl, $content, FILE_APPEND | LOCK_EX );
    }

    public function parseFileInfo(){
        if(!isEmpty($this->fileUrl)){
            $index      = strripos($this->fileUrl, "/");

            /*若最后一个字符是斜杠，则过滤该斜杠*/
            if($index == strlen($this->fileUrl) - 1){
                $this->fileUrl = substr($this->fileUrl, 0, strlen($this->fileUrl) - 1);
            }

            $filePath   = substr($this->fileUrl, 0, $index);
            $fileName   = substr($this->fileUrl, $index +1);

            $this->filePath = $filePath;
            $this->fileName = $fileName;
        }
    }

    /**
     * 创建目录
     */
    public function mkdir(){
        if(strlen($this->filePath) > 0){
            if(!is_dir($this->filePath)){
                mkdir(iconv("UTF-8", "GBK", $this->filePath),0777,true);
            }
        }
    }

    /**
     * 返回文件所在目录的绝对路径
     * @return string 返回文件所在目录的绝对路径
     */
    public function getRealPath(){
        return realpath($this->filePath);
    }

    /**
     * 返回文件的绝对路径，包含文件名
     * @return string   返回文件的绝对路径，包含文件名
     */
    public function getRealFileUrl(){
        return $this->getRealPath().'/'.$this->fileName;
    }
}