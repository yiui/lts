<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use yii;
use yii\base\Action;

class LogoutAction extends Action {

    public function run(){
        Yii::$app->user->logout();

        return $this->controller->redirect(Yii::$app->params['web_domain']);
    }
}