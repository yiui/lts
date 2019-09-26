<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use common\models\User;
use yii;
use yii\base\Action;
use yii\helpers\StringHelper;

class CkLoginAction extends Action {

    public function run(){
        //判断来源，防盗链
        if (strpos(Yii::$app->request->referrer,Yii::$app->request->hostInfo)===0) {
            if (!Yii::$app->request->isAjax or !Yii::$app->request->isGet){
                return 'error';
            }

            if (Yii::$app->user->isGuest){
                return 0;
            }else{
                return Yii::$app->user->id;
            }
        }else{
            return '欢迎访问：'.Yii::$app->name.' - '.Yii::$app->request->hostInfo;
        }
    }
}