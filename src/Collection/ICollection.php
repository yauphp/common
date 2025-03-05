<?php
namespace Yauphp\Common\Collection;

/**
 * 集合接口
 * @author Tomix
 *
 */
interface ICollection extends \Countable,IStreamable
{
    /**
     * 元素计数
     */
    function size();

    /**
     * 集合是否为空
     */
    function isEmpty();

    /**
     * 添加元素
     * @param mixed $entry
     * @return ICollection
     */
    function add($entry);

    /**
     * 移除元素
     * @param mixed $entry
     * @return ICollection
     */
    function remove($entry);

    /**
     * 返回第一个元素
     */
    function first();

    /**
     * 返回最后一个元素
     */
    function last();

    /**
     * 是否包含某个元素
     * @param mixed $entry
     */
    function contains($entry);

    /**
     * 清空所有元素
     * @return ICollection
     */
    function clear();
}

