<?php
namespace Yauphp\Common\Collection;

/**
 * 队列流操作类(内部测试版本)
 * @author Tomix
 *
 */
class Stream implements IStream
{
    private const ACTION_FILTER=1;
    private const ACTION_MAP=2;
    private const ACTION_FLAT=4;
    private const ACTION_DISTINCT=8;
    private const ACTION_SORT=16;

    /**
     * 操作列表(Action,Function)
     * @var array
     */
    private $actions=[];

    /**
     * 元素实体集
     * @var array
     */
    private $entries=[];

    /**
     * 构造函数
     * @param array $entries
     */
    public function __construct(array $entries,array $actions=[]){
        $this->entries=$entries;
        $this->actions=$actions;
    }

    /**
     * 克隆流对象
     * @return IStream
     */
    public function clone(){
        return new Stream($this->entries,$this->actions);
    }

    /**
     * 过滤元素
     * @return IStream
     */
    public function filter(\Closure $filter){
        $this->actions[]=[self::ACTION_FILTER,$filter];
        return $this->clone();
    }

    /**
     * 映射元素
     * @return IStream
     */
    public function map(\Closure $mapper){
        $this->actions[]=[self::ACTION_MAP,$mapper];
        return $this->clone();
    }

    /**
     * 队列降维(扁平化)
     * @return IStream
     */
    public function flat(){
        $this->actions[]=[self::ACTION_FLAT];
        return $this->clone();
    }

    /**
     * 获取不重复元素集(比较函数优先:$comparer>in_array)
     * @param \Closure $comparer
     * @return IStream
     */
    public function distinct(\Closure $comparer=null){
        $this->actions[]=[self::ACTION_DISTINCT,$comparer];
        return $this->clone();
    }

    /**
     * 队列排序
     * @return IStream
     */
    public function sort(\Closure $comparer=null){
        $this->actions[]=[self::ACTION_SORT,$comparer];
        return $this->clone();
    }

    /**
     * 元素计数
     */
    public function count(){
        return count($this->execute());
    }

    /**
     * 转换为数组
     * @return array
     */
    public function toArray(){
        return $this->execute();
    }

    /**
     * 转换为键值对数组
     * @return array
     */
    public function toMap(\Closure $keyMapper,\Closure $valueMapper=null){
        $kvs=[];
        foreach ($this->toArray() as $el){
            $key=$keyMapper($el);
            $value=!empty($valueMapper)?$valueMapper($el):$el;
            $kvs[$key]=$value;
        }
        return $kvs;
    }

    /**
     * 分组并转换为键值对二维数组
     * @return array
     */
    public function groupBy(\Closure $keyMapper,\Closure $valueMapper=null){
        $kvs=[];
        foreach ($this->toArray() as $el){
            $key=$keyMapper($el);
            $value=!empty($valueMapper)?$valueMapper($el):$el;
            if(!array_key_exists($key, $kvs)){
                $kvs[$key]=[];
            }
            $kvs[$key][]=$value;
        }
        return $kvs;
    }

    /**
     * 执行流操作
     * @return array
     */
    private function execute(){
        //没有操作时,直接返回原始数据
        if(count($this->actions)==0){
            return $this->entries;
        }

        //因为排序操作需要收集完所有的元素才能操作,所以把作用函数按排序函数间隔分组
        //因为降维操作后提取出来的函数无需经过前面的作用函数,而要经过后续作用函数,所以也需要按降维函数分组
        $actionGroups=[];
        $index=0;
        foreach ($this->actions as $action){
            if($index==0||$action[0]==self::ACTION_SORT||$action[0]==self::ACTION_FLAT){
                $actionGroups[]=[$action];
            }else{
                $actionGroups[count($actionGroups)-1][]=$action;
            }
            $index++;
        }

        //按组对队列使用作用函数
        $values=$this->entries;
        foreach ($actionGroups as $actions){
            //按分组的规则,排序函数或降维函数总是在第一个,且每组最多只有一个排序或降维
            $first=$actions[0];
            //排序
            if($first[0]==self::ACTION_SORT){
                $first[1]?usort($values, $first[1]):sort($values);
                unset($actions[0]);
            }
            //除维
            if($first[0]==self::ACTION_FLAT){
                $appendValues=[];
                for($i=count($values)-1;$i>=0;$i--){
                    $value=$values[$i];
                    if(is_array($value)){
                        $appendValues=array_merge($appendValues,$value);
                        unset($values[$i]);
                    }
                }
                $values=array_merge($values,$appendValues);
            }

            //执行除排序外的所有规则
            $_values=[];
            foreach ($values as $value){
                $hasValue=true;
                foreach ($actions as $action){
                    $actionName=$action[0];
                    $actionHandler=$action[1]??null;
                    if($hasValue && $actionName==self::ACTION_FILTER && !empty($actionHandler)){
                        $hasValue=$actionHandler($value);
                    }else if($hasValue && $actionName==self::ACTION_MAP && !empty($actionHandler)){
                        $value=$actionHandler($value);
                    }else if($hasValue && $actionName==self::ACTION_DISTINCT){
                        //比较函数:$comparer>in_array
                        $hasValue=$actionHandler?empty(array_uintersect($_values,[$value],$actionHandler)):!in_array($value, $_values);
                    }
                }
                if($hasValue){
                    $_values[]=$value;
                }
            }

            $values=$_values;
        }
        return $values;
    }
}

