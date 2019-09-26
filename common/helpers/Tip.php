<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/13
 * Time: 14:23
 */
namespace common\helpers;

use yii\helpers\Url;

class Tip {

    /**
     * 返回提示链接给前端使用
     * $this->redirect(Tip::getTip($msg,$title,$type,$goto,$time));
     *
     * @param $msg 提示信息
     * @param null $title 提示标题，默认根据错误类型进行提示
     * @param int $type 错误类型，0为成功，1: 操作失败; 2: 请重试; 3: 暂不可用;
     * @param null $goto 跳转到哪里？默认空 为上一页，支持绝对URL或Url::to模式，(但不支持 只是action名)，必须包含控制器和方法如 site/tip
     * @param int $time 多少秒后自动跳转
     * @return string
     */
    public static function getTip($msg,$title=null,$type=0,$goto=null,$time=3){
        return ['site/tip',
            'msg'=>urlencode($msg),
            'title'=>empty($title)?null:urlencode($title),
            'type'=>$type,
            'goto'=>$goto?(is_array($goto)?Url::to($goto):urlencode($goto)):null,
            'time'=>$time
        ];
    }
}