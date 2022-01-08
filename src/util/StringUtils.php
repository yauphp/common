<?php
namespace yauphp\common\util;

/**
 * 字符串处理常用类
 * @author Tomix
 *
 */
class StringUtils
{
    /**
     * 移除UTF8文件的BOM信息
     * @param string $input
     * @return string
     */
    public static function removeUtf8Bom($input)
    {
        $charset=[];
        $charset[1] = substr($input, 0, 1);
        $charset[2] = substr($input, 1, 1);
        $charset[3] = substr($input, 2, 1);
        if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191){
            $rest = substr($input, 3);
            return $rest;
        }
        return $input;
    }

    /**
     * 下横线转为驼峰命名表示法
     * @param string $value 要转换的字符串
     * @param string $firstUpperCase 是否第一个字母为大写，默认为小写
     */
    public static function toHumpString($value,$firstUpperCase=false)
    {
        $len=strlen($value);
        if($len==0){
            return $value;
        }

        //前面出现的下划线
        $preLines="";
        for($i=0;$i<$len;$i++){
            $s=substr($value, $i,1);
            if($s!="_"){
                break;
            }
            $preLines.="_";
        }

        $value= ucwords(str_replace("_", " ", $value));
        $value= str_replace(" ","",$value);
        if(!$firstUpperCase){
            $value=lcfirst($value);
        }
        return $preLines.$value;
    }

    /**
     * 驼峰转下横线
     * @param string $value
     * @return string
     */
    public static function toUnderlineString($value)
    {
        $str = preg_replace_callback("/([A-Z]{1})/",function($matches){
            return "_".strtolower($matches[0]);
        },$value);
        if(strpos($str, "_")===0){
            $str=substr($str, 1);
        }
        return $str;
    }

    /**
     * 取中文字串拼音第一个字母组成的字符串
     * @param string $zh
     */
    public static function getFirstCharString($zh)
    {
        $ret = "";
        $s1 = iconv("UTF-8","gb2312", $zh);
        $s2 = iconv("gb2312","UTF-8", $s1);
        if($s2 == $zh){$zh = $s1;}
        for($i = 0; $i < strlen($zh); $i++){
            $s1 = substr($zh,$i,1);
            $p = ord($s1);
            if($p > 160){
                $s2 = substr($zh,$i++,2);
                $ret .= self::getFirstchar($s2);
            }else{
                $ret .= $s1;
            }
        }
        return $ret;
    }

    /**
     * 取中文字串拼音第一个字母
     * @param unknown $zh
     * @return string|NULL
     */
    public static function getFirstchar($zh)
    {
        $fchar = ord($zh[0]);
        if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($zh[0]);
        //$s1 = iconv("UTF-8","gb2312//IGNORE", $zh);
        // $s2 = iconv("gb2312","UTF-8//IGNORE", $s1);
        $s1 = self::convertEncoding($zh,"GB2312");
        $s2 = self::convertEncoding($s1,"UTF-8");
        if($s2 == $zh){$s = $s1;}else{$s = $zh;}
        $asc = ord($s[0]) * 256 + ord($s[1]) - 65536;
        if($asc >= -20319 and $asc <= -20284) return "A";
        if($asc >= -20283 and $asc <= -19776) return "B";
        if($asc >= -19775 and $asc <= -19219) return "C";
        if($asc >= -19218 and $asc <= -18711) return "D";
        if($asc >= -18710 and $asc <= -18527) return "E";
        if($asc >= -18526 and $asc <= -18240) return "F";
        if($asc >= -18239 and $asc <= -17923) return "G";
        if($asc >= -17922 and $asc <= -17418) return "I";
        if($asc >= -17417 and $asc <= -16475) return "J";
        if($asc >= -16474 and $asc <= -16213) return "K";
        if($asc >= -16212 and $asc <= -15641) return "L";
        if($asc >= -15640 and $asc <= -15166) return "M";
        if($asc >= -15165 and $asc <= -14923) return "N";
        if($asc >= -14922 and $asc <= -14915) return "O";
        if($asc >= -14914 and $asc <= -14631) return "P";
        if($asc >= -14630 and $asc <= -14150) return "Q";
        if($asc >= -14149 and $asc <= -14091) return "R";
        if($asc >= -14090 and $asc <= -13319) return "S";
        if($asc >= -13318 and $asc <= -12839) return "T";
        if($asc >= -12838 and $asc <= -12557) return "W";
        if($asc >= -12556 and $asc <= -11848) return "X";
        if($asc >= -11847 and $asc <= -11056) return "Y";
        if($asc >= -11055 and $asc <= -10247) return "Z";
        return null;
    }

    /**
     * 转换字符编码
     * @param unknown $data
     * @param unknown $to
     * @return string
     */
    public static function convertEncoding($data,$to)
    {
        $encode_arr=["UTF-8","ASCII","GBK","GB2312","BIG5","JIS","eucjp-win","sjis-win","EUC-JP"];
        $encoded=mb_detect_encoding($data, $encode_arr);
        $data = mb_convert_encoding($data,$to,$encoded);
        return $data;
    }

    /**
     * 把键值对数组拼装成url参数字符串
     * @param array $params     键值对数组
     * @param string|array $removeKey 移除的键
     * @deprecated 使用UrlUtil::joinUrlParams
     */
    public static function joinUrlParams($params=[],$removeKeys=[])
    {
        if(!is_array($removeKeys)){
            $removeKeys=[$removeKeys];
        }
        $url="";
        foreach ($params as $k=>$v){
            if(!in_array($k, $removeKeys)){
                $url.="&".$k."=".urlencode($v);
            }
        }
        if(!empty($url)){
            $url=substr($url, 1);
        }
        return $url;
    }

    /**
     * 是否以某字符串开始
     * @param string $input
     * @param string $substr
     * @return boolean
     */
    public static function startWith($input,$substr)
    {
        if(strlen($substr)==0){
            return false;
        }
        return strpos($input, $substr)===0;
    }

    /**
     * 是否以某字符串结束
     * @param string $input
     * @param string $substr
     * @return boolean
     */
    public static function endWith($input,$substr)
    {
        if(strlen($substr)==0){
            return false;
        }
        return strrpos($input, $substr)===(strlen($input)-strlen($substr));
    }
}

