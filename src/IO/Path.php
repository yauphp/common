<?php
namespace Yauphp\Common\IO;

/**
 * 路径相关
 * @author Administrator
 *
 */
class Path
{
    /**
     * 获取文件目录名
     * @param string $path
     * @return mixed
     */
    public static function getDirName($path)
    {
        if(!empty($path)){
            return pathinfo($path,PATHINFO_DIRNAME);
        }
        return "";
    }

    /**
     * 取得文件名
     * @param string $path
     * @return string
     */
    public static function getFileBaseName($path)
    {
        if(!empty($path)){
            return pathinfo($path,PATHINFO_BASENAME);
        }
        return "";
    }

    /**
     * 取得文件名,不包括扩展名
     * @param string $path
     * @return string
     */
    public static function getFileBaseNameWithoutExt($path)
    {
        $baseName = pathinfo($path,PATHINFO_BASENAME);
        $extName= self::getFileExtName($path);
        $pos=strrpos($baseName, $extName);
        if($pos>0){
            $baseName=substr($baseName, 0,$pos-1);
        }
        return $baseName;
    }

    /**
     * 取得文件扩展名
     * @param string $path
     * @return string
     */
    public static function getFileExtName($path)
    {
        return pathinfo($path,PATHINFO_EXTENSION);
    }

    /**
     * 连接路径字符串
     * @param string[] $paths
     * @return string
     */
    public static function combinePath(...$paths)
    {
        //$paths=func_get_args();
        $value="";
        for($i=0;$i<count($paths);$i++){
            $path=$paths[$i];
            if($i>0){
                $value.="/";
                $path=ltrim($path,"/");
            }
            if($i<count($paths)-1){
                $path=rtrim($path,"/");
            }
            $value.=$path;
        }
        return $value;
    }
}

