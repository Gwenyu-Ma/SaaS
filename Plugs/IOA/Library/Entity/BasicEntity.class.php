<?php
/**
 * Created by PhpStorm.
 * User: zqf
 * Date: 2016-09-07
 * Time: 19:15
 */

namespace Plugs\IOA\Library\Entity;
use Plugs\IOA\Library\Exception\CommonException;


/**
 * 实体基础类
 * Class BasicEntity
 * @package Common\Entity
 */
class BasicEntity {
    /*构造器，通过反射技术，将各个参数映射到实体类中*/
    public function __construct(){

    }

    /**
     * 通过魔术方法实现javabean的get方法
     * @param $name    参数名
     * @return mixed   自动调用对应的方法名
     * @throws CommonException
     */
    public function __get($name){
        $getter = 'get'.ucfirst($name);

        if (method_exists($this, $getter)) {
            return $this->$getter();
        }elseif (method_exists($this, 'set'.$name)){
            throw new CommonException(false, 'read-only property:'.$name);
        }else{
            throw new CommonException(false, 'unknown property:'.$name);
        }
    }

    /**
     * 通过魔术方法实现javabean的set方法
     * @param $name   参数名
     * @param $value  参数值
     * @throws CommonException
     */
    public function __set($name, $value){
        $setter = 'set'.ucfirst($name);

        if (method_exists($this, $setter)) {
            $this->$setter($value);
        }elseif (method_exists($this, 'get'.$name)){
            throw new CommonException(false, 'write-only property:'.$name);
        }else {
            throw new CommonException(false, 'unknown property:'.$name);
        }
    }

    /**
     * 通过反射机制实现返回json数据
     * @param $unescaped  boolean  类名
     * @return string
     */
    public function getJson($unescaped = false){
        $result     = array();

        $className  = get_called_class();

        $reflector  = new \ReflectionClass($className);

        $properties = $reflector->getProperties();

        foreach ($properties as $property) {
            $propertyName   = $property->getName();
            $getter         = 'get'.ucfirst($propertyName);

            if (method_exists($this, $getter)) {
                $propertyValue = $this->$getter();
                $result[$propertyName] = $propertyValue;
            }
        }

        /*写入日志不进行转码，方便查看日志*/
        if($unescaped){
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        } else{
            return json_encode($result);
        }
    }
}