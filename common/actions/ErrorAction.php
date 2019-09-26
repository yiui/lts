<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use yii;
use yii\base\Action;
use common\models\LoginForm;
use common\models\LoginRecord;
use common\helpers\Ip2Addr;

class ErrorAction extends Action {

    public function run(){
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            /**
             * 创建一个视图文件为views/site/error.php，
             * 在该视图文件中，如果错误动作定义为yii\web\ErrorAction，
             * 可以访问该动作中定义的如下变量：
            name: 错误名称
            message: 错误信息
            exception: 更多详细信息的异常对象，如HTTP 状态码，错误码， 错误调用栈等。
             */
            return $this->controller->render('@common/views/site/error', ['exception' => $exception]);
        }
    }
}