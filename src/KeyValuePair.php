<?php
namespace Yauphp\Common;

class KeyValuePair
{
    private $key;
    private $value;

    public function __construct($key,$value){
        $this->key=$key;
        $this->value=$value;
    }

    /**
     * @return the $key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return the $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param field_type $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @param field_type $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }



}

