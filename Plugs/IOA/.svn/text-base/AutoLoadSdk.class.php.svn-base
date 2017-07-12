<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-11-28
 * Time: 16:51
 */

/**
 * 自动加载sdk管理
 * Class AutoLoadSdk
 */
class AutoLoadSdk {
    /*自动加载实例化对象*/
    private static $instance;

    /*ioaSdk运行目录*/
    private $ioaSdkRuntimeDir;

    /*sdk文件管理*/
    private static $sdkFileMap = array();

    /*sdk类库根目录名称*/
    private $libDir;

    /*类文件后缀*/
    private $ext = ".class.php";

    /**
     * ioaSdk管理类
     * IoaSdk constructor.
     */
    private function __construct(){
        $this->ioaSdkRuntimeDir = dirname(__FILE__) ;
        $this->autoloadPath     = $this->ioaSdkRuntimeDir;
        $this->libDir           = DIRECTORY_SEPARATOR."Plugs".DIRECTORY_SEPARATOR."IOA".DIRECTORY_SEPARATOR;

        /*不需要的加载的目录*/
        $this->conf["skip_dir_names"] = array(
            ".svn",
            ".idea"
        );

        $this->conf["allow_file_extension"] = "php";
    }

    /**
     * 实例化对象
     * @return AutoLoadSdk
     */
    public static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new AutoLoadSdk();
        }

        return self::$instance;
    }

    /**
     * 自动加载ioaSdk
     */
    public function autoLoadSdk(){
        /*检查是否初次加载sdk文件，进行sdk初始化操作*/
        if(!self::$sdkFileMap){
            /*对各个目录进行扫描，并记录加载的文件*/
            $this->scanDirs(array(
                $this->autoloadPath
            ));
        }

        /*实现类预加载*/
        spl_autoload_register(array($this, "loadClass"));
    }

    /**
     * 目录扫描,
     * Using iterative algorithm scanning subdirectories
     * save autoloader filemap
     *
     * @param array $dirs one-dimensional
     * @param $dirs
     */
    private function scanDirs($dirs){
        $i = 0;

        while (isset($dirs[$i])) {
            $dir = $dirs[$i];

            $files = scandir($dir);

            foreach ($files as $file) {
                if (in_array($file, array(".", "..")) || in_array($file, $this->conf["skip_dir_names"])) {
                    continue;
                }

                $currentFile = $dir . DIRECTORY_SEPARATOR . $file;

                if (is_file($currentFile)) {
                    $this->addFileMap($currentFile);
                } else if (is_dir($currentFile)) {
                    // if $currentFile is a directory, pass through the next loop.
                    $dirs[] = $currentFile;
                } else {
                    trigger_error("$currentFile is not a file or a directory.");
                }
            }

            unset($dirs[$i]);

            $i ++;
        }
    }

    /**
     * 将文件加在map中
     * @param $file   文件路径
     * @return bool
     */
    private function addFileMap($file){
        $libNames = $this->parseLibNames(trim(file_get_contents($file)));

        foreach ($libNames as $libType => $libArray) {

            if($libType != "function"){
                $method = "addClass";

                foreach ($libArray as $libName) {
                    $this->$method($libName, $file);
                }
            }
        }

        return true;
    }

    /**
     * 加载类
     * @param $className    类名
     * @param $file         文件地址
     * @return bool
     */
    private function addClass($className, $file){
        $key = $this->getKey($className, $file);
		
        if (!array_key_exists($key, self::$sdkFileMap)) {
            self::$sdkFileMap[$key] = $file;
        }
    }

    /**
     * 生成文件map中的key，
     * 默认以类文件路径生成key，最好命名空间必须与文件路径一致
     * @param $className 类名
     * @param $file      全路径的文件名
     * @return string
     */
    private function getKey($className, $file){
        $key    = strtolower($className);
        $file   = strtolower($file);
        $libdir = strtolower($this->libDir);

        /*类文件可能没有使用命名空间*/
        if(strpos($file, $this->ext) !== false){
            $file       = explode($this->ext,$file);
            $file       = $file[0];

            $namespace  = explode($libdir,$file);
            if($namespace){
                $namespace  = $namespace[1];
                $key        =  $libdir . $namespace;
            }
        }

        $key = str_replace(DIRECTORY_SEPARATOR, '.', $key);

        $key = substr($key,0,1) != "." ? $key : substr($key,1, strlen($key));

        return $key;
    }

    /**
     * 自动加载类
     * @param $className    类名
     * @throws Exception
     */
    private function loadClass($className){
        $className = strtolower($className);
        $className = str_replace(DIRECTORY_SEPARATOR, ".", $className);
        $className = str_replace("\\", ".", $className);

        /*自动加载含有命名空间的类*/
        if(array_key_exists($className, self::$sdkFileMap)){
            if(isset(self::$sdkFileMap[$className])){
                if ($classFile = self::$sdkFileMap[$className]) {
                    include($classFile);
                } else{
                    throw new Exception($className .'类文件不存在');
                }
            }
        } else{
            throw new Exception($className ."未定义命名空间或类文件不存在");
        }
    }

    /**
     * 解析源文件
     * @param $src  文件源代码
     * @return array
     */
    private function parseLibNames($src){
        $libNames = array();
        $tokens = token_get_all($src);
        $level = 0;
        $found = false;
        $name = '';

        foreach ($tokens as $token) {

            if (is_string($token)) {
                if ('{' == $token) {
                    $level ++;
                }
                else if ('}' == $token) {
                    $level --;
                }
            }
            else {
                list($id, $text) = $token;

                if (T_CURLY_OPEN == $id || T_DOLLAR_OPEN_CURLY_BRACES == $id) {
                    $level ++;
                }

                if (0 < $level) {
                    continue;
                }

                switch ($id) {
                    case T_STRING:
                        if ($found) {
                            $libNames[strtolower($name)][] = $text;
                            $found = false;
                        }
                        break;
                    /*暂不支持对函数文件自动加载*/
                    case T_FUNCTION:
                        break;
                    case T_CLASS:
                    case T_INTERFACE:
                        $found = true;
                        $name = $text;
                        break;
                }
            }
        }

        return $libNames;
    }
}