<?php
namespace Yauphp\Common\Util;

class DateTimeUtils
{
    private static $timeZones=[];

    public static function formatTimestamp($format="Y-m-d H:i:s", $timestamp = 0, $timeZoneId = null){

        $date = new \DateTime();
        if(!empty($timestamp)){
            if(strlen($timestamp)>10){
                $timestamp=substr($timestamp, 0,10);
            }
            $date->setTimestamp($timestamp);
        }else {
            $date->setTimestamp(time());
        }
        if(!empty($timeZoneId)){
            $timeZone=null;
            if(array_key_exists($timeZoneId, self::$timeZones)){
                $timeZone=self::$timeZones[$timeZoneId];
            }else {
                $timeZone=new \DateTimeZone($timeZoneId);
                self::$timeZones[$timeZoneId]=$timeZone;
            }
            $date->setTimezone($timeZone);
        }
        return $date->format($format);
    }
}