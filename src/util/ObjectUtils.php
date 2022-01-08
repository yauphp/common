<?php
namespace yauphp\common\util;

/**
 * 对象常用功能类
 * @author Tomix
 *
 */
class ObjectUtils
{
    /**
     * 根据属性名获取getter方法名
     * @param object|string $class  对象或类型名
     * @param string $propertyName  属性名
     * @return string
     */
    public static function getGetter($class,$propertyName)
    {
        $getter = "get" . ucfirst($propertyName);
        if (method_exists($class, $getter)) {
            return $getter;
        }
        return null;
    }

    /**
     * 根据属性名获取setter方法名
     * @param object|string $class  对象或类型名
     * @param string $propertyName  属性名
     * @return string
     */
    public static function getSetter($class,$propertyName)
    {
        $setter = "set" . ucfirst($propertyName);
        if (method_exists($class, $setter)) {
            return $setter;
        }
        return null;
    }

    /**
     * 是否有属性getter
     * @param object|string $class  对象或类型名
     * @param string $propertyName  属性名
     */
    public static function hasGetter($class,$propertyName)
    {
        return !empty(self::getGetter($class, $propertyName));
    }

    /**
     * 是否有属性setter
     * @param object|string $class  对象或类型名
     * @param string $propertyName  属性名
     */
    public static function hasSetter($class,$propertyName)
    {
        return !empty(self::getSetter($class, $propertyName));
    }

    /**
     * 是否同时有属性setter与getter
     * @param object|string $class  对象或类型名
     * @param string $propertyName  属性名
     */
    public static function hasGetterAndSetter($class,$propertyName)
    {
        return self::hasGetter($class, $propertyName)&&self::hasSetter($class, $propertyName);
    }

    /**
     * 设置对象属性
     * @param object $obj           对象
     * @param string $propertyName  属性名
     * @param mixed $value          属性值
     * @param bool $fieldAccess     setter不存在时,是否允许通过公开的字段访问
     * @return void
     */
    public static function setPropertyValue($obj,$propertyName,$value,$fieldAccess=false)
    {
        $setter=self::getSetter($obj, $propertyName);
        if(!empty($setter)){
            $obj->$setter($value);
        }else if($fieldAccess && property_exists($obj, $propertyName)){
            $obj->$propertyName=$value;
        }
    }

    /**
     * 获取对象属性值
     * @param object $obj           对象
     * @param string $propertyName  属性名
     * @param bool $fieldAccess     getter不存在时,是否允许通过公开的字段访问
     * @return mixed
     */
    public static function getPropertyValue($obj,$propertyName,$fieldAccess=false)
    {
        $getter=self::getGetter($obj, $propertyName);
        if(!empty($getter)){
            return $obj->$getter();
        }else if($fieldAccess && property_exists($obj, $propertyName)){
            return $obj->$propertyName;
        }
    }

    /**
     * 复制对象属性
     * @param object $srcObject    源对象
     * @param object $destObject   目标对象
     * @param array $fieldMap      字段映射
     * @param string $fieldAccess  是否允许直接访问公开字段
     * @return boolean
     */
    public static function copyPropertyValues($srcObject,$destObject,$fieldMap=null,$fieldAccess=false)
    {
        if(!is_object($srcObject)||!is_object($destObject)){
            return false;
        }
        $destFields=[];
        if($fieldAccess){
            $destFields=array_keys(get_object_vars($destObject));
        }
        if($fieldMap==null){
            $fieldMap=[];
        }
        foreach (get_object_vars($srcObject) as $prop=>$value){
            $destProp=array_key_exists($prop, $fieldMap)?$fieldMap[$prop]:$prop;
            if(self::hasSetter($destObject, $destProp)){
                self::setPropertyValue($destObject, $destProp, $value);
            }else if(in_array($destProp, $destFields)){
                $destObject->$destProp=$value;
            }
        }
    }

    /**
     * 简单复制对象属性(源对象通过字段读取,目标对象通过setter或字段写入)
     * @param object $srcObject                 源对象
     * @param object $destObject                目标对象
     * @param array $excludedProperties         排除字段(针对源对象)
     * @param boolean $fieldAccess              是否允许直接访问公开字段(针对目标对象)
     * @return boolean
     */
    public static function simpleCopyProperties($srcObject,$destObject,$excludedProperties=null,$fieldAccess=false)
    {
        if(!is_object($srcObject)||!is_object($destObject)){
            return false;
        }
        if($excludedProperties==null){
            $excludedProperties=[];
        }

        //目标对象字段集
        $destFields=[];
        if($fieldAccess){
            $destFields=array_keys(get_object_vars($destObject));
        }

        //从源对象字段复制
        foreach (get_object_vars($srcObject) as $prop=>$value){
            if(!in_array($prop, $excludedProperties)){
                if(self::hasSetter($destObject, $prop)){
                    $setter=self::getSetter($destObject, $prop);
                    $destObject->$setter($value);
                }else if(in_array($prop, $destFields)){
                    $destObject->$prop=$value;
                }
            }
        }

    }
}

