<?php
/**
 * Created by PhpStorm.
 * User: samsun
 * Date: 2017/11/23
 * Time: 10:05
 */
namespace common\helpers;
class StaticMapHelper
{
    //百度地图的url
    private static $_url='http://api.map.baidu.com/staticimage/v2?';
    //百度地图的ak
    private static $_ak='N1UqIzEqUrOf1NfU6CBzOYqlN0B2Rr9v';
    //图片的宽
    private static $_width='1000';
    //图片的长
    private static $_height='500';
    //高清图范围[3, 18]；低清图范围[3,19]
    private static $_zoom=15;
    //mcode安全码。若为Android/IOS SDK的ak, 该参数必需。
//    private $_mocde='3.1415926';


    public static function StaticMapPic($position){
      if(!is_array($position) || empty($position)){
          return false;
      }
      $position_str=implode(',',$position);
        $params=[
            'ak'=>self::$_ak,
            'center'=>$position_str,
            'width'=>self::$_width,
            'height'=>self::$_height,
            'zoom'=>self::$_zoom,
            'markers'=>$position_str,
            'markerStyles'=>'L,A,0xFF0000'  //标注的大小，label，颜色
        ];
        $params_str=http_build_query($params,'','&');
        $doUrl=self::$_url.$params_str;
       return $doUrl;
    }
}