<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/7/21
 * Time: 8:34
 */
namespace common\helpers;

use common\models\Area;
use common\models\Shops;
use Yii;
use yii\web\Cookie;

class AreaHelper {
    const AREA_ID_NAME='swAreaId';

    /**
     * 获取现在选择的区域ID,用于地区切换等
     * @return null|string
     */
    public static function nowAreaId(){
        if (Yii::$app->request->cookies->has(self::AREA_ID_NAME)) {
            $a_cookie = Yii::$app->request->cookies->get(self::AREA_ID_NAME);//对象
            return $a_cookie->value;//直接打印也是可以的,但其并不是数字而是对象
        }else{
            return null;
        }
    }

    /**
     * 获取字母和城市/省份
     * @param null $letter 获取某个首字母或null时获取所有字母
     * @param bool $is_area 获取城市?否为省份
     * @return array
     */
    public static function letterAll($letter=null,$is_area=true){
        if ($is_area and $letter) {
            $c=['and',['letter'=>$letter],['IS NOT','parent_id',null]];//某个字母的城市
        }elseif ($is_area and !$letter){
            $c=['and',['IS NOT','letter',null],['IS NOT','parent_id',null]];//$letter=null 为获取所有字母的城市
        }elseif (!$is_area and $letter){
            $c=['letter'=>$letter,'parent_id'=>null];//某字母的省份
        }else{
            $c=['and',['IS NOT','letter',null],['parent_id'=>null]];//所有字母的省份
        }

        $all=Area::find()->select('id,name,letter')->where($c)->asArray()->all();
        $letter_area=[];
        foreach ($all as $area){
            $letter_area[$area['letter']][$area['id']]=$area['name'];
        }

        return $letter_area;
    }

    /**
     * 返回店铺的省市区，用于列表显示区域使用
     * @param Shops $shop 店铺对象
     * @return string 省市
     */
    public static function getShopCity($shop,$all=false){
        if ($shop->is_com){
            $address=$shop->shopAddress;
        }elseif($shop->parent_id){
            $address=$shop->parent->shopAddress;
        }else{
            return null;
        }
        if (!$address){
            return null;
        }

        $p=$address->getProvinceName();
        $c=$address->getCityName();

        $city='';
        if ($p == $c) {
            if (strpos($p, '省') or strpos($p, '市')) {
                $city= mb_substr($p, 0, mb_strlen($p) - 1);
            }
        } else {
            $city= Str::rtrim($p, '省') . ' ' . Str::rtrim($c, '市');
        }

        if ($all){
            return $city.' '.$address->detaile;
        }else {
            return $city;
        }
    }

    /**
     * 设置区域ID
     * 第二次请求才有效
     * 前台必须以 get 请求，参数必须是 AreaHelper::AREA_ID_NAME
     */
    public static function setAreaId(){
        if (isset($_GET[AreaHelper::AREA_ID_NAME]) and is_numeric($_GET[AreaHelper::AREA_ID_NAME])){
            if ($_GET[AreaHelper::AREA_ID_NAME]==0){
                Yii::$app->response->cookies->remove(AreaHelper::AREA_ID_NAME);
            }else{
                Yii::$app->response->cookies->add(new Cookie(['name'=>AreaHelper::AREA_ID_NAME,'value'=>$_GET[AreaHelper::AREA_ID_NAME]]));
            }
        }
    }
}