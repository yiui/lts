<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/7/21
 * Time: 8:34
 */
namespace common\helpers;

class ArrayHelper {
    /**
     * 将一个数字转成字符串，用分隔符隔开
     * @param array $array
     * @param string $delimiter
     * @return string
     */
    public static function toStr($array=[],$delimiter=''){
        $str='';
        foreach ($array as $k => $v){
            if (is_array($v)){
                $str.=self::toStr($v,$delimiter);
            }else{
                $str.=($k+1).'. '.$delimiter.$v.$delimiter;
            }
        }

        return rtrim($str,$delimiter);
    }
}