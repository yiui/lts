<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class UeditorController extends Controller
{
    public function init(){
        parent::init();

        //CSRF 基于 POST 验证，UEditor 无法添加自定义 POST 数据，同时由于这里不会产生安全问题，故简单粗暴地取消 CSRF 验证。
        //如需 CSRF 防御，可以使用 server_param 方法，然后在这里将 Get 的 CSRF 添加到 POST 的数组中。。。
        Yii::$app->request->enableCsrfValidation = false;

        //do something
        //这里可以对扩展的访问权限进行控制
    }

    // more modify ...
    // 更多的修改

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'up' => [
                //定义 error 动作使用 yii\web\ErrorAction 类， 该类渲染名为error视图来显示错误。
                'class' => 'common\actions\UeditorAction',
                //使用此，错误动作和错误视图已经定义好了。
            ],
        ];
    }
}