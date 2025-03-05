<?php

namespace Yauphp\Common\Collection;

/**
 * 集合类
 * @author Tomix
 *
 */
class ArrayCollection implements ICollection
{
    /**
     * 元素实体集
     * @var array
     */
    private $entries=[];

    /**
     * 构造函数
     * @param array $entries
     */
    public function __construct(array $entries = [])
    {
        $this->entries = array_values($entries);
    }

    /**
     * 从数组创建对象
     * @param array $entries
     * @return ArrayCollection
     */
    public static function parse(array $entries=[]){
        return new ArrayCollection($entries);
    }


    /**
     * 元素计数(与count功能一样)
     */
    public function size(){
        return count($this->entries);
    }

    /**
     * 集合是否为空
     */
    public function isEmpty(){
        return empty($this->entries);
    }

    /**
     * 添加元素
     * @param mixed $entry
     * @return ICollection
     */
    public function add($entry){
        $this->entries[]=$entry;
        return $this;
    }

    /**
     * 移除元素
     * @param mixed $entry
     * @return ICollection
     */
    public function remove($entry){
        $key = array_search($entry, $this->entries, true);
        if ($key === false) {
            return false;
        }
        unset($this->entries[$key]);
        return $this;
    }

    /**
     * 返回第一个元素
     */
    public function first(){
        return reset($this->entries);
    }

    /**
     * 返回最后一个元素
     */
    public function last(){
        return end($this->entries);
    }

    /**
     * 是否包含某个元素
     * @param mixed $entry
     */
    public function contains($entry){
        return in_array($entry, $this->entries, true);
    }

    /**
     * 清空所有元素
     * @return ICollection
     */
    public function clear(){
        $this->entries=[];
        return $this;
    }

    /**
     * 元素计数
     * {@inheritDoc}
     * @see Countable::count()
     */
    public function count (){
        return $this->size();
    }

    /**
     * 转换为流对象
     * @return IStream
     */
    public function stream(){
        return new Stream($this->entries);
    }
}

