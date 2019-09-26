<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/7/21
 * Time: 8:34
 */
namespace common\helpers;

use common\models\CarNew;
use common\models\CarNewExtePic;
use common\models\CarNewInPic;
use common\models\CarNewMotorPic;
use common\models\CarOld;
use common\models\CarOldExtePic;
use common\models\CarOldInPic;
use common\models\CarOldMotorPic;
use common\models\PackFlushProd;
use common\models\PackFlushShop;
use common\models\PackShopActi;
use common\models\PackShopKefu;
use common\models\PackShopTurn;
use common\models\ShopActi;
use common\models\ShopConfig;
use common\models\ShopKefu;
use common\models\ShopPower;
use common\models\ShopTurn;
use common\models\ShopUpdating;
use Yii;

class PackHelper {
    /**
     * 查找店铺轮播图功能包使用情况
     * ['total'=>可以发布的总数,'used'=>已经发布的数量,'usable'=>剩余数量];
     * @param integer $shop_id 店铺ID号
     * @return array 使用情况数组
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public static function getShopTurnInfo($shop_id){
        $sql1='SELECT SUM(`times`) AS times FROM `'.PackShopTurn::getTableSchema()->name.'` WHERE `shop_id`=:sid AND `status`=:s AND `over_time` > :t';
        $res1=Yii::$app->db->createCommand($sql1)->bindValues([':sid' => $shop_id, ':s' => PackShopTurn::STATUS_ACTIVE,':t'=>time()])->queryOne();
        if (!$res1 or !isset($res1['times'])){
            $total= 0;//找不到功能包总张数
            $use = 0;
        }else {
            $total=$res1['times'];
            $sql2 = 'SELECT COUNT(`id`) AS num FROM `'.ShopTurn::getTableSchema()->name.'` WHERE `shop_id`=:sid';
            $res2 = Yii::$app->db->createCommand($sql2)->bindValues([':sid' => $shop_id])->queryOne();
            if (!$res2 or !isset($res2['num'])) {
                $use = 0;//找不到功能包使用数
            } else {
                $use = $res2['num'];
            }
        }

        return ['total'=>$total,'used'=>$use,'usable'=>$total-$use];
    }

    /**
     * 查找店铺客服功能包使用情况
     * ['total'=>可以发布的总数,'used'=>已经发布的数量,'usable'=>剩余数量];
     * @param integer $shop_id 店铺ID号
     * @return array 使用情况数组
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public static function getShopKefuInfo($shop_id){
        $sql1='SELECT SUM(`times`) AS times FROM `'.PackShopKefu::getTableSchema()->name.'` WHERE `shop_id`=:sid AND `status`=:s AND `over_time` > :t';
        $res1=Yii::$app->db->createCommand($sql1)->bindValues([':sid' => $shop_id, ':s' => PackShopKefu::STATUS_ACTIVE,':t'=>time()])->queryOne();
        if (!$res1 or !isset($res1['times'])){
            $total= 0;//找不到功能包总张数
            $use = 0;
        }else {
            $total=$res1['times'];
            $sql2 = 'SELECT COUNT(`id`) AS num FROM `'.ShopKefu::getTableSchema()->name.'` WHERE `shop_id`=:sid';
            $res2 = Yii::$app->db->createCommand($sql2)->bindValues([':sid' => $shop_id])->queryOne();
            if (!$res2 or !isset($res2['num'])) {
                $use = 0;//找不到功能包使用数
            } else {
                $use = $res2['num'];
            }
        }

        return ['total'=>$total,'used'=>$use,'usable'=>$total-$use];
    }

    /**
     * 查找店铺活动功能包使用情况
     * ['total'=>可以发布的总数,'used'=>已经发布的数量,'usable'=>剩余数量];
     * @param integer $shop_id 店铺ID号
     * @return array 使用情况数组
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public static function getShopActiInfo($shop_id){
        $sql1='SELECT SUM(`times`) AS times FROM `'.PackShopActi::getTableSchema()->name.'` WHERE `shop_id`=:sid AND `status`=:s AND `over_time` > :t';
        $res1=Yii::$app->db->createCommand($sql1)->bindValues([':sid' => $shop_id, ':s' => PackShopActi::STATUS_ACTIVE,':t'=>time()])->queryOne();
        if (!$res1 or !isset($res1['times'])){
            $total= 0;//找不到功能包总张数
            $use = 0;
        }else {
            $total=$res1['times'];
            $sql2 = 'SELECT COUNT(`id`) AS num FROM `'.ShopActi::getTableSchema()->name.'` WHERE `shop_id`=:sid';
            $res2 = Yii::$app->db->createCommand($sql2)->bindValues([':sid' => $shop_id])->queryOne();
            if (!$res2 or !isset($res2['num'])) {
                $use = 0;//找不到功能包使用数
            } else {
                $use = $res2['num'];
            }
        }

        return ['total'=>$total,'used'=>$use,'usable'=>$total-$use];
    }

    /**
     * 查找店铺刷新包功能包使用情况
     * ['usable'=>剩余数量];
     * @param integer $shop_id 店铺ID号
     * @return array 使用情况数组
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public static function getShopFlushInfo($shop_id){
        $sql1='SELECT SUM(`times`) AS times FROM `'.PackFlushShop::getTableSchema()->name.'` WHERE `shop_id`=:sid AND `status`=:s AND `over_time` > :t';
        $res1=Yii::$app->db->createCommand($sql1)->bindValues([':sid' => $shop_id, ':s' => PackFlushShop::STATUS_ACTIVE,':t'=>time()])->queryOne();
        if (!$res1 or !isset($res1['times'])){
            $total= 0;//找不到功能包总张数
        }else {
            $total=$res1['times'];
        }

        return ['usable'=>$total];
    }

    /**
     * 查找店铺刷新功能包使用情况
     * ['usable'=>剩余数量];
     * @param integer $shop_id 店铺ID号
     * @return array 使用情况数组
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public static function getProdFlushInfo($shop_id){
        $sql1='SELECT SUM(`times`) AS times FROM `'.PackFlushProd::getTableSchema()->name.'` WHERE `shop_id`=:sid AND `status`=:s AND `over_time` > :t';
        $res1=Yii::$app->db->createCommand($sql1)->bindValues([':sid' => $shop_id, ':s' => PackFlushProd::STATUS_ACTIVE,':t'=>time()])->queryOne();
        if (!$res1 or !isset($res1['times'])){
            $total= 0;//找不到功能包总张数
        }else{
            $total= $res1['times'];
        }

        return ['usable'=>$total];
    }

    /**
     * 返回某新车轮播图使用情况
     * ['total'=>可以发布的总数,'used'=>已经发布的数量,'usable'=>剩余数量];
     * @param integer $car_id 新ID
     * @return array 使用情况数组
     * @param string $now_type 当前正在发布的图片类型，以此准确判断当前可用数量
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException
     * @throws int
     */
    public static function getCarNewTurnInfo($car_id,$now_type=null){
        $vip = ShopPower::CkPower(CarNew::getTableSchema()->name);
        $total_num = ShopConfig::CAR_NEW_CONFIG[$vip]['turn_num'];//可以管理的总数量
        $e_num = CarNewExtePic::find()->where(['car_id' => $car_id])->count('id');//已经发布的外观数量
        $i_num = CarNewInPic::find()->where(['car_id' => $car_id])->count('id');//已经发布的内饰数量
        $m_num = CarNewMotorPic::find()->where(['car_id' => $car_id])->count('id');//已经发布的发动机数量
        $now_num=$e_num+$i_num+$m_num;//已经发布的总数
        switch ($now_type){
            case 'e':
                $not_num=$e_num;//不计入使用量的数量。比如：在上传外观图片时，usable 表示整个外观可用的数量（包含以前的外观图片数量）
                break;
            case 'i':
                $not_num=$i_num;
                break;
            case 'm':
                $not_num=$m_num;
                break;
            default:
                $not_num=0;
                break;
        }
        return ['total'=>$total_num,'used'=>$now_num,'usable'=>$total_num - $now_num+$not_num];
    }

    /**
     * 返回某二手车轮播图使用情况
     * ['total'=>可以发布的总数,'used'=>已经发布的数量,'usable'=>剩余数量];
     * @param integer $car_id 二手车ID
     * @param string $now_type 当前正在发布的图片类型，以此准确判断当前可用数量
     * @return array 使用情况数组
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\ForbiddenHttpException
     * @throws int
     */
    public static function getCarOldTurnInfo($car_id,$now_type=null){
        $vip = ShopPower::CkPower(CarOld::getTableSchema()->name);
        $total_num = ShopConfig::CAR_OLD_CONFIG[$vip]['turn_num'];//可以管理的总数量
        $e_num = CarOldExtePic::find()->where(['car_id' => $car_id])->count('id');//已经发布的外观数量
        $i_num = CarOldInPic::find()->where(['car_id' => $car_id])->count('id');//已经发布的内饰数量
        $m_num = CarOldMotorPic::find()->where(['car_id' => $car_id])->count('id');//已经发布的发动机数量
        $now_num=$e_num+$i_num+$m_num;//已经发布的总数
        switch ($now_type){
            case 'e':
                $not_num=$e_num;//不计入使用量的数量。比如：在上传外观图片时，usable 表示整个外观可用的数量（包含以前的外观图片数量）
                break;
            case 'i':
                $not_num=$i_num;
                break;
            case 'm':
                $not_num=$m_num;
                break;
            default:
                $not_num=0;
                break;
        }
        return ['total'=>$total_num,'used'=>$now_num,'usable'=>$total_num - $now_num+$not_num];
    }
}