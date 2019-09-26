<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use common\helpers\Str;
use yii;
use yii\base\Action;
use yii\helpers\Url;

class TipAction extends Action {

    /**
     * 操作提示页面
     * @param $msg 提示信息，请使用urlencode()编码
     * @param null $title 提示标题，请使用urlencode()编码
     * @param int $type 错误类型，0为成功
     * @param null $goto 跳转到哪里？默认空 为上一页，支持绝对URL或Url::to模式，请使用urlencode('/site/tip')编码
     * @param int $time 多少秒后自动跳转
     * @return string
     */
    public function run($msg,$title=null,$type=0,$goto=null,$time=3){
        //解析标题
        if (empty($title)) {
            switch ($type) {
                case 1:
                    $title = '操作失败';
                    break;
                case 2:
                    $title = '请重试';
                    break;
                case 3:
                    $title = '暂不可用';
                    break;
                default:
                    $title = '操作成功';
                    break;
            }
        }else{
            $title=Str::notag(urldecode($title));
        }

        /**
         * 分析出具体需要跳转的路径
         */
        if (empty($goto)) {
            $goto = 'javascript:history.go(-1)';//返回上一页面
        }else{
            $goto=Str::notag(urldecode($goto));

            //地址为：site/login 格式
            if (strpos($goto,'http')===0 or strpos($goto,'/')!==false){
                $goto=$goto;
            }else{
                $goto='javascript:history.go(-1)';//不支持HTTP直接挑战
            }
        }

        $this->controller->layout='@common/views/layouts/main-login';

        return $this->controller->render('@common/views/site/tip', [
            'goto'=>$goto,
            'title'=>$title,
            'msg'=>Str::notag(urldecode($msg)),
            'type'=>$type,
            'time'=>$time,
        ]);
    }
}