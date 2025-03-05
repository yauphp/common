<?php
namespace Yauphp\Common\IO;

/**
 * 文件操作类
 * @author Tomix
 *
 */
class File
{
    /**
     * 创建目录
     * @param $path
     * @return void
     */
    public static function createDir($path)
    {
        if (!file_exists($path)){
            self::createDir(dirname($path));
            mkdir($path, 0777);
            chmod($path,0777);
        }
    }

    /**
     * 删除目录及目录下所有子目录与文件
     * @param $path
     * @param $deleteEmptyParentFolder如果父目录为空,是否同时删除
     * @return bool
     */
    public static function deleteDir($path,$deleteEmptyParentFolder=false)
    {
        //给定的目录不是一个文件夹
        if(!is_dir($path)){
            return false;
        }
        $fh = opendir($path);
        while(($row = readdir($fh)) !== false){
            //过滤掉虚拟目录
            if($row == '.' || $row == '..'){
                continue;
            }

            if(!is_dir($path.'/'.$row)){
                unlink($path.'/'.$row);
            }
            self::deleteDir($path.'/'.$row);
        }
        //关闭目录句柄，否则出Permission denied
        closedir($fh);
        //删除文件之后再删除自身
        if(!rmdir($path)){
            return false;
        }

        //是否删除上级空目录
        if($deleteEmptyParentFolder){
            $dir=dirname($path);
            while($dir && $dir != ".." && $dir != "."){
                if(is_dir($dir) && self::isEmptyDir($dir)){
                    @rmdir($dir);
                    $dir=dirname($dir);
                }else{
                    break;
                }
            }
        }
        return true;
    }


    /**
     * 拷貝文件
     * @param $source
     * @param $path
     * @param $replace
     */
    public static function copyFile($source,$dest,$replace=false)
    {
        $dir=dirname($dest);
        if (!file_exists($dir)){//如果文件夹不存在
            self::createDir(dirname($dir));    //取得最后一个文件夹的全路径返回开始的地方
            mkdir($dir, 0777);
            chmod($dir,0777);
        }
        if(file_exists($dest)){
            if($replace)
                unlink($dest);
            else
                return false;
        }
        return copy($source,$dest);
    }

    /**
     * 删除文件
     * 当第二个参数为true时,同时删除所在的空目录
     * @param $path
     * @param $deleteEmptyFolder
     * @return bool
     */
    public static function deleteFile($path,$deleteEmptyFolder=false)
    {
        //删除文件
        $blReturn=@unlink($path);

        //是否删除空目录
        if($blReturn && $deleteEmptyFolder){
            $dir=dirname($path);
            while($dir && $dir != ".." && $dir != "."){
                if(is_dir($dir) && self::isEmptyDir($dir)){
                    @rmdir($dir);
                    $dir=dirname($dir);
                }else{
                    break;
                }
            }
        }

        //返回值
        return $blReturn;
    }

    /**
     * 判断目录是否为空
     * @param unknown $dir
     * @return boolean
     */
    public static function isEmptyDir($dir)
    {
        $handle = opendir($dir);
        if($handle){
            $item = readdir($handle);
            while($item){
                if ($item != "." && $item != ".."){
                    @closedir($handle);
                    return false;
                }
                $item = readdir($handle);
            }
        }
        return true;
    }
}

