<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/6/30
 * Time: 17:40
 */
namespace common\helpers;

use yii\db\Query;

class IdcardHelper {
    public static function getInfo($idcard){
        //1-6地区代码，1-2省，3-4市，5-6县区
//        $area=substr($idcard,0,2);//1-2省
//        $area=substr($idcard,2,2);//3-4市
//        $area=substr($idcard,4,2);//5-6县区

        //查找地址
        $info=array();
        $a1=substr($idcard,0,2).'0000';//省
        $a2=substr($idcard,0,4).'00';//市
        $a3=substr($idcard,0,6);//区

        if ($address=(new Query())->select('name')->from('area')->where(['in','id',[$a1,$a2,$a3]])->limit(3)->all()){
            $info['address']='';
            foreach ($address as $add){
                $info['address'].=$add['name'].' ';
            }
        }

        $info['y']=substr($idcard,6,4);//年
        $info['m']=substr($idcard,10,2);//月
        $info['d']=substr($idcard,12,2);//日

        $info['sex']=substr($idcard,14,3)%2==0?'女':'男';//顺序号，单数是男的

        return $info;
    }
}