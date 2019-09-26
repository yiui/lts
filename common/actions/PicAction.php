<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use common\models\Config;
use Yii;
use common\models\Pic;
use common\helpers\Base64;
use yii\base\Action;

class PicAction extends Action {

    /**
     * 暂未完成
     * $token 加密数据，包含路径和过期时间信息
     * Created by www.yiui.top.
     * User: Zhao Wenming
     * @param $token 游客或非自身，必须使用token，否则 token=图片ID 即可
     */
    public function run($token){
        if (Yii::$app->user->isGuest or !is_numeric($token)){
            $token=Yii::$app->security->decryptByKey(Base64::decode($token),Pic::PRIVATE_PWD);//解码
            $token=json_decode($token);

            if (!isset($token->t) or !isset($token->path)){
                return '抱歉，图片请求出错！欢迎访问：'.Yii::$app->params['web_name'].' - '.Yii::$app->params['web_domain'];
            }

            $time=$token->t;
            if (time() > $time){
                return '抱歉，图片访问超时！欢迎访问：'.Yii::$app->params['web_name'].' - '.Yii::$app->params['web_domain'];
            }
            $path=$token->path;
            //在后面还应调用 yii\web\Response::send() 没有其他内容追加到响应中。
        }else{
            if ($pic=Pic::findOne(['id'=>$token,'user_id'=>Yii::$app->user->id])){
                $path=$pic->path;
            }else{
                $path='private'.DIRECTORY_SEPARATOR.'nopic.png';
            }
        }

        $file=Config::STATIC_DIR_PATH.$path;
        if (is_file($file)){
            return Yii::$app->response->sendFile($file)->send();
        }else{
            $defImg='private'.DIRECTORY_SEPARATOR.'nopic.png';
            return Yii::$app->response->sendFile(Config::STATIC_DIR_PATH.$defImg)->send();
        }
    }

}