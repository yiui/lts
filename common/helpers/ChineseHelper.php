<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/6/13 Time: 13:07
 */
namespace common\helpers;

class ChineseHelper {

    /**
     * 判断是不是全部是中文
     * @param $str
     * @return bool
     */
    public static function is_allChinese($str){
        if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $str)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 判断是不是含有中文
     * @param $str
     * @return bool
     */
    public static function is_haveChinese($str){
        if(preg_match('/[\x{4e00}-\x{9fa5}]+/u', $str)){
            return true;
        }else{
            return false;
        }
    }
}