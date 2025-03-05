<?php
namespace Yauphp\Common\Collection;

/**
 * IStream接口
 * @author Tomix
 *
 */
interface IStream extends \Countable
{
    /**
     * 克隆流对象
     * @return IStream
     */
    function clone();

    /**
     * 过滤元素
     * @return IStream
     */
    function filter(\Closure $filter);

    /**
     * 映射元素
     * @return IStream
     */
    function map(\Closure $mapper);

    /**
     * 队列降维(扁平化)
     * @return IStream
     */
    function flat();

    /**
     * 获取不重复元素集(比较函数优先:$comparer>equals>in_array)
     * @param \Closure $comparer
     * @return IStream
     */
    function distinct(\Closure $comparer=null);

    /**
     * 队列排序
     * @return IStream
     */
    function sort(\Closure $comparer=null);

    /**
     * 转换为数组
     * @return array
     */
    function toArray();

    /**
     * 转换为键值对数组
     * @return array
     */
    function toMap(\Closure $keyMapper,\Closure $valueMapper=null);

    /**
     * 分组并转换为键值对二维数组
     * @return array
     */
    function groupBy(\Closure $keyMapper,\Closure $valueMapper=null);
}

