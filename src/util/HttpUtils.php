<?php
namespace Yauphp\Common\Util;

/**
 *
 * @author Administrator
 *
 */
class HttpUtils
{
    /**
     * 创建CURL
     * @param unknown $url
     * @param string $isPost
     * @param mixed $postData
     * @param number $timeoutSeconds
     * @param boolean $sslVerifypeer
     */
    public static function newCurl($url,$isPost=false,$postData=null,$timeoutSeconds=30,$sslVerifypeer=null){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeoutSeconds);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, $isPost);
        if($isPost){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        if(!is_null($sslVerifypeer)){
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,$sslVerifypeer);
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,$sslVerifypeer);//严格校验
        }
        return $ch;
    }

    /**
     * CURL GET请求
     * @param unknown $url
     * @param number $timeoutSeconds
     * @param boolean $sslVerifypeer
     * @throws \Exception
     * @throws Exception
     * @return string
     */
    public static function get($url,$timeoutSeconds=30,$sslVerifypeer=null){
        $ch=self::newCurl($url,false,null,$timeoutSeconds,$sslVerifypeer);
        try{
            $rsp = curl_exec($ch);
            if(!empty(curl_errno($ch))){
                throw new \Exception(curl_error($ch),curl_errno($ch));
            }
            return $rsp;
        }catch (\Exception $ex){
            throw $ex;
        }finally {
            curl_close($ch);
        }
    }

    /**
     * CURL POST请求
     * @param unknown $url
     * @param unknown $data
     * @param number $timeoutSeconds
     * @param unknown $sslVerifypeer
     * @throws \Exception
     * @throws Exception
     * @return mixed
     */
    public static function post($url,$data,$timeoutSeconds=30,$sslVerifypeer=null){
        $ch=self::newCurl($url,true,$data,$timeoutSeconds,$sslVerifypeer);
        try{
            $rsp = curl_exec($ch);
            if(!empty(curl_errno($ch))){
                throw new \Exception(curl_error($ch),curl_errno($ch));
            }
            return $rsp;
        }catch (\Exception $ex){
            throw $ex;
        }finally {
            curl_close($ch);
        }
    }

}

