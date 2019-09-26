<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use yii;
use yii\base\Action;
use common\helpers\Yzm;

class VadPhoneYzmAction extends Action {

    /**
     * 验证验证码是否正确
     * @param null $name 验证码字段名，用于接收验证码
     * @param null $YzmName 验证码session名，用于取出验证码
     * @return int
     */
    public function run($name=null,$YzmName=null){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //验证
        if (!Yii::$app->request->isAjax or !Yii::$app->request->isPost){
            return ['status'=>400,'msg'=>'请求有误'];
        }

        if (empty($name)){
            $name='phoneYzm';
        }

        if (!$yzm=Yii::$app->request->post($name)){
            return ['status'=>400,'msg'=>'参数错误'];
        }

        if (empty($YzmName)){
            $YzmName='phone_yzm';
        }
        if(Yzm::validYzm($yzm,$YzmName)){
            return ['status'=>0,'msg'=>'验证成功！'];
        }else{
            return ['status'=>1,'msg'=>'验证失败！'];
        }
    }
}