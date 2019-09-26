<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/30
 * Time: 17:50
 *
 * yii2日志记录初探
 */
namespace backend\controllers;

use yii;
use yii\rest\ActiveController;

class ActiveLogController extends ActiveController
{
    public function beforeAction($action)
    {
        parent::beforeAction($action);//进行父类重写
        $controller = Yii::$app->controller->id;//控制器名称
        $action = $this->action->id;//方法名称
        $ip = $_SERVER["REMOTE_ADDR"];//用户IP
        $info = 'IP:'.$ip.'----'.'操作URL：'.$controller.'/'.$action;
        file_put_contents("a.txt",$info);//模拟插入数据库暂时写入文件中，你可以直接插入数据库
        return true;
    }

}
