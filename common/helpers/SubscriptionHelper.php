<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2018/2/12
 * Time: 12:58
 */
namespace common\helpers;

use common\models\NotifyToSubscription;

use yii;

class SubscriptionHelper {

    /**
     * 增加订阅
     * @param string $behavior 行为名
     * @param $target_tb 目标表名
     * @param $target_id 目标ID
     * @return mixed 订阅结果
     */
    public static function add($behavior,$target_tb,$target_id){
        $nts=new NotifyToSubscription();
        $nts->scenario=NotifyToSubscription::SCENARIO_USER_CREATE;
        $nts->behavior=$behavior;//订阅的行为名，根据此行为遍历出所有的操作，插入每个操作的订阅记录
        $nts->target_tb=$target_tb;
        $nts->target_id=$target_id;
        return $nts->save();
    }

}