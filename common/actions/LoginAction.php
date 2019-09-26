<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use common\models\Spread;
use common\models\SpreadUser;
use common\models\User;
use yii;
use yii\base\Action;
use common\models\LoginForm;
use common\models\LoginRecord;
use common\helpers\Ip2Addr;
use yii\web\Cookie;

class LoginAction extends Action {
    public $layout='@frontend/views/layouts/main-login';//登录布局
    public $view='@common/views/site/login';//登录试图文件

    /**
     * 调用方法：
     * 'login' => [
     *      'class' => 'common\actions\LoginAction',
     *      //'layout'=>'main-login',//空为公共的布局
     *      //'view'=>'/site/login',//空为公共的登录视图文件
     * ],
     *
     * @return string|yii\web\Response
     */
    public function run(){
        if (!Yii::$app->user->isGuest) {
            return $this->controller->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //查找之前的登陆记录
            $loginrecord=LoginRecord::find()->select('ip,created_at')->where(['user_id'=>Yii::$app->user->id])->orderBy('created_at DESC')->limit(1)->one();

            $newaddr=Ip2Addr::ipqqwry(Yii::$app->request->userIP);

            if ($loginrecord){
                $oldaddr=Ip2Addr::ipqqwry(long2ip((int)$loginrecord->ip));
                if (($d=intval((time()-$loginrecord->created_at)/(3600*24)))==0){
                    $info=((time()-$loginrecord->created_at)/3600).'小时';
                }else{
                    $info=$d.'天';
                }
                $info='上次登陆时间为'.date('Y-m-d H:i:s', $loginrecord->created_at).'，距离上次登陆已有'.$info;
            }

            if (isset($oldaddr['country']) and $newaddr['country']!=$oldaddr['country']){
                Yii::$app->session->setFlash('欢迎从 '.$oldaddr['country'].' 来到 '.$newaddr['country']).$info;
            }else{
                Yii::$app->session->setFlash('欢迎来到：'.$newaddr['country']);
            }

            //保存登陆记录
            (new LoginRecord())->save();

            //记录推广信息
            if (Yii::$app->request->cookies->has('SpreadCode')){
                $spread_cookie=Yii::$app->request->cookies->get('SpreadCode');
                if ($spread=Spread::findOne(['code'=>$spread_cookie->value])){
                    if ($spread_user=SpreadUser::find()->where(['user_id'=>Yii::$app->user->id])->limit(1)->one()){
                        if ($spread_user->spread_id==$spread->id){
                            //不需要更新
                            Yii::$app->response->cookies->remove($spread_cookie);
                        }else{
                            $spread_user->scenario=SpreadUser::SCENARIO_ADMIN_UPDATE;
                            $spread_user->spread_id=$spread->id;
                            if ($spread_user->save(false)){
                                //更新成功
                                Yii::$app->response->cookies->remove($spread_cookie);
                            }
                        }
                    }else{
                        $spread_user=new SpreadUser();
                        $spread_user->scenario=SpreadUser::SCENARIO_USER_CREATE;
                        $spread_user->spread_id=$spread->id;
                        if ($spread_user->save(false)) {
                            //保存成功
                            Yii::$app->response->cookies->remove($spread_cookie);
                        }
                    }
                }
            }

            return $this->controller->goBack();
        } else {
            $this->controller->layout=$this->layout;
            return $this->controller->render($this->view, [
                'model' => $model,
            ]);
        }
    }
}