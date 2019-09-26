<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2018/2/12
 * Time: 12:58
 */
namespace common\helpers;

use common\models\Notify;

use yii;

class RemindHelper {

    /**
     * 对某个目标的某操作创建一个提醒
     * @param $target_tb 目标表名
     * @param $target_id    目标ID
     * @param $action 操作名，请见 @see common\models\NotifyReason
     * @param $content 提醒内容
     * @param $pri 提醒优先级 9最大
     * @return bool 是否创建成功
     */
    public static function add($target_tb,$target_id,$action,$content=null,$pri=0){
        $notify=new Notify();
        $notify->scenario=Notify::SCENARIO_TX;
        $notify->target_tb=$target_tb;
        $notify->target_id=$target_id;
        $notify->action=$action;
        $notify->content=$content;
        $notify->pri=$pri;
        return $notify->save();
    }

}