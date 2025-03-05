<?php
namespace Yauphp\Common\Util;

/**
 * URL辅助类
 * @author Tomix
 *
 */
class UrlUtils
{
    /**
     * 获取基本URL
     * @param string $url
     * @return string
     */
    public static function getBaseUrl($url){
        $pos=strpos($url, "?");
        if($pos===false){
            return $url;
        }else if($pos===0){
            return "";
        }else{
            return substr($url, 0, $pos);
        }
    }

    /**
     * 获取URL查询参数(点号与空格会转为下划线)
     * @param string $url
     * @return array
     */
    public static function getUrlParams($url){
        $pos=strpos($url, "?");
        if($pos===false || $pos==strlen($url)-1){
            return [];
        }
        $paramStr=substr($url, $pos+1);
        $values=[];
        parse_str($paramStr, $values);
        return $values;
    }

    /**
     * 把键值对数组拼装成url参数字符串
     * @param array $params     键值对数组
     * @param string|array $removeKey 移除的键
     */
    public static function joinUrlParams(array $params=[],$removeKeys=[])
    {
        if(!is_array($removeKeys)){
            $removeKeys=[$removeKeys];
        }
        $params=array_filter($params,function($key) use($removeKeys){
            return !in_array($key, $removeKeys);
        },ARRAY_FILTER_USE_KEY);
        if(empty($params)){
            return "";
        }
        return http_build_query($params);
    }

    /**
     * 附加参数到URL(已存在的参数会被覆盖)
     * @param string $url
     * @param array $appendParams
     * @return string
     */
    public static function appendUrlParams($url,$appendParams=[],$removeKeys=[])
    {
        if(empty($appendParams)){
            return $url;
        }
        $baseUrl=self::getBaseUrl($url);
        $params=self::getUrlParams($url);
        $params=array_merge($params,$appendParams);
        $paramStr=self::joinUrlParams($params,$removeKeys);
        if(empty($paramStr)){
            return $baseUrl;
        }
        return $baseUrl."?".$paramStr;
    }

    /**
     * 从URL移除参数
     * @param string $url
     * @param array $removeKeys
     */
    public static function removeUrlParams($url,$removeKeys)
    {
        if(empty($removeKeys)){
            return $url;
        }
        $baseUrl=self::getBaseUrl($url);
        $params=self::getUrlParams($url);
        $paramStr=self::joinUrlParams($params,$removeKeys);
        if(empty($paramStr)){
            return $baseUrl;
        }
        return $baseUrl."?".$paramStr;
    }
}

