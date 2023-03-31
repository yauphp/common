<?php
namespace Yauphp\Common\Date;

class TimeZone
{
    /**
     *
     * @var \DateTimeZone
     */
    private $timezone;

    public function __construct($timeZoneId){
        $this->timezone=new \DateTimeZone($timeZoneId);
    }

    /**
     *
     * @return \DateTimeZone
     */
    public function getDateTimeZone() : \DateTimeZone{
        return $this->timezone;
    }

    public function getName () {
        return $this->timezone->getName();
    }

    public function getOffset () {
        return $this->timezone->getOffset(new \DateTime());
    }

    public function getOffsetHours ($padLeftZero = false, $padLeftPositiveSign = false){

        $offset=$this->getOffset();
        $hours=$offset/3600;
        $str=abs($hours);

        if($padLeftZero){
            $str=str_pad($str, 2, "0", STR_PAD_LEFT);
        }
        if($padLeftPositiveSign && $offset>=0){
            $str="+".$str;
        }else if($offset<0){
            $str="-".$str;
        }
        return $str;
    }

    public function getOffsetMinutes (){
        return $this->getOffset()/60;
    }

    public function getTransitions ($timestamp_begin, $timestamp_end) {

        return $this->timezone->getTransitions($timestamp_begin, $timestamp_end);
    }

    public function getLocation () {

        return $this->timezone->getLocation();
    }
}

