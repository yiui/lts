<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 *
 * @var User $user
 */
namespace common\actions;

use common\models\Pic;
use common\models\Status;
use common\models\User;
use yii;
use yii\base\Action;
use yii\helpers\StringHelper;

class UserInfoAction extends Action {

    public function run(){
        //判断来源，防盗链
        if (strpos(Yii::$app->request->referrer,Yii::$app->request->hostInfo)===0) {
            if (!Yii::$app->request->isAjax or !Yii::$app->request->isGet) {
                return 'error';
            }
            if (Yii::$app->user->isGuest){
                return 0;
            }else{
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if ($user=(new yii\db\Query())->from(User::getTableSchema()->name)->select('user.id as uid,pic.path as img,user.username as name')->leftJoin(Pic::getTableSchema()->name,'user.pic_id=pic.id')->where('user.id=:uid AND user.status_id='.Status::v2I(User::getTableSchema()->name,User::STATUS_ACTIVE),[':uid'=>Yii::$app->user->id])->limit(1)->one()) {
                    if (empty($user['img'])){
                        $user['img']='tx/tx_00000.jpg';
                    }
                    return ['uid' => $user['uid'],'img'=>$user['img'],'name'=>$user['name']];
                }
            }
        } else {
            return '欢迎访问：'.Yii::$app->name.' - '.Yii::$app->request->hostInfo;
        }
    }
}