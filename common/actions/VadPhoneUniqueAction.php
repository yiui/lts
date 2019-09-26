<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use yii;
use yii\base\Action;
use common\helpers\Yzm;

class VadPhoneUniqueAction extends Action {
    public $tbname='user';//验证哪个表里面的手机号码是否存在

    /**
     * 验证哪个表里面的手机号码是否存在
     */
    public function run($name='phone'){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //验证
        if (!Yii::$app->request->isAjax or !Yii::$app->request->isPost){
            return ['status'=>400,'msg'=>'请求有误'];
        }

        if (empty($name)){
            $name='phone';
        }

        if (!$phone=Yii::$app->request->post($name)){
            return ['status'=>400,'msg'=>'参数错误'];
        }

        if((new yii\db\Query())->from($this->tbname)->select('id')->where([$name=>$phone])->limit(1)->one()){
            return ['status'=>1,'msg'=>'此手机号码已经被占用！'];
        }else{
            return ['status'=>0,'msg'=>'手机可以使用！'];
        }
    }
}