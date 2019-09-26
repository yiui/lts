<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/25
 * Time: 13:32
 */

namespace console\controllers;

use yii\console\Controller;

class HelloController extends Controller
{
    public $message;


    public function actionList(){
        return [1,2,3,4];
    }

    public function options($actionID)
    {
        return ['message'];
    }

    public function optionAliases()
    {
        return ['m' => 'message'];
    }

    public function actionIndex()
    {
        echo $this->message . "\n";
    }
}