<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use yii;
use yii\base\Action;
use common\helpers\Yzm;
use common\components\Aliyun\DaYu;

class SendPhoneYzmAction extends Action {
    public $signName=Dayu::SMS_COMMON_NAME;// 短信签名
//    public $signName=Dayu::SMS_TEST_NAME;// 短信签名
    public $templateCode=DaYu::SMS_ID_VALID_CODE;// 短信模板编号
    public $str=null;// 短信模板中字段的值数组，['code'=>$code]

    /**
     * @param null $name 手机号码字段名，用于给此手机发送验证码
     * @param null $YzmName 验证码名，用于保存的session名
     * @return int|string
     */
    public function run($name=null,$YzmName=null){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //验证
        if (!Yii::$app->request->isAjax or !Yii::$app->request->isPost){
            return ['status'=>400,'msg'=>'请求有误'];
        }

        if (empty($phone)){
            $name='phone';
        }

        if (!$phone=Yii::$app->request->post($name)){
            return ['status'=>400,'msg'=>'参数错误'];
        }
        if (!preg_match('/^1[34578][0-9]{9}$/', $phone)) {
            return ['status'=>400,'msg'=>'输入的并不是手机号码！'];
        }

        //判断来源，防盗链
        if (strpos(Yii::$app->request->referrer,Yii::$app->request->hostInfo)===0) {
            if (empty($YzmName)){
                $YzmName='phone_yzm';
            }
            $code = Yzm::getYzm($YzmName);
            $fa = new DaYu();

            if (empty($this->str)){
                $send=['code'=>$code];
            }else{
                $send=array_merge(['code'=>$code],$this->str);
            }

            $response = $fa->sendSms(
                $this->signName, // 短信签名
                $this->templateCode, // 短信模板编号
                $phone, // 短信接收者
                // 短信模板中字段的值
                $send
            );

            if ($response) {
                return ['status'=>0,'msg'=>'发送成功'];
            } else {
                return ['status'=>1,'msg'=>'发送失败，请重试！'];
            }
        }else{
            return ['status'=>403,'msg'=>'请勿盗链！欢迎访问：'.Yii::$app->name.' - '.Yii::$app->request->hostInfo];
        }
    }
}