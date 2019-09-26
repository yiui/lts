<?php

namespace backend\controllers;

use davidxu\igt\Getui;
use Yii;
use common\components\Getuis;
use yii\web\Controller;


/**
 *
 *
 * TaskController implements the CRUD actions for Task model.
 */
class TestController extends Controller
{


    /**
     * 接口测试
     *个推接口测试
     *
     * @return mixed
     */
    public function actionGetui()
    {


//        // 配置（替换成自己的）
//        $Appid = "TP2nAqAVRQ7CA7iwCbQZD1";
//        $Appkey = "jENYldWIQy5NachBrZ0ZP8";
//        $Mastersecret = "1bXdeL3g347IgFEuqmQ4a4";
////    $this->appId = 'TP2nAqAVRQ7CA7iwCbQZD1';
////    $this->appkey = 'jENYldWIQy5NachBrZ0ZP8';
////    $this->masterSecret = '1bXdeL3g347IgFEuqmQ4a4';
//// 实例化
//        $gpush = new hhGpush($Appid,$Appkey,$Mastersecret);
//
//// 发送推送
//        $gpush->PushMsgToSingle([
//            "clientid"=> "a400f160e080c19a13353217f1cacfe8",
//            "event"=> "warning",
//            "title"=> "健康告知",
//            "content"=> "您的中二病已经很严重了！",
//            "push"=> "outer",
//            "system"=> "ios",
//            "silent"=> false
//        ]);
//
//// 打印结果
//        var_dump($gpush->result);exit;
        $getui = new Getuis();

        //单条消息透传
        //  $res = $getui->pushTransmissionSilenceToSingle($cid, '内容123');
//$res=$getui->pushTransmissionAlertToSingle($cid,$content,'内容','标题');
 $arr= [
          ['cid'=>'86a1b154484a5d1a2b4686be09bdeb92','title'=>'标题111','text'=>'内容111','tcontent'=>json_encode(["message" => ["payload" => ["pagepath" => base64_encode("/pages/subPackages/myTask/taskDetailApp/taskDetailApp?camp_id=18&user_id=65&type=3")]]])],
          ['cid'=>'8a6fa09c50d0792dce96714ccb057152','title'=>'标题222','text'=>'内容222','tcontent'=>json_encode(["message" => ["payload" => ["pagepath" => base64_encode("/pages/subPackages/myTask/taskDetailApp/taskDetailApp?camp_id=18&user_id=65&type=3")]]])]
       ];
//             foreach ($arr as $k=>$v){
//                 $getui->pushTotificationTemplateToSingle($v['cid'], $v['title'], $v['text'], $v['tcontent']);
//             }
        $str=date("Y-m-d H:i:s",time());
        $tcontent=json_encode(["message" => ["payload" => ["pagepath" => base64_encode("/pages/subPackages/myTask/taskDetailApp/taskDetailApp?camp_id=18&user_id=65&type=3")]]]);
        $res=$getui->pushTotificationTemplateToSingle('a073c460ce60dc5ce4eca4d50c5ac32c','标题11111','内容6666',$tcontent);
    //  var_dump($res);
        //单个苹果推送
       $res=$getui->IGtTransmissionTemplateToSingleApple('0dd5edf9d0c5afef06b3afb0f741a0cb','标题11111','这是内容',$tcontent);



        var_dump($res);
    }

public function actionHello(){var_dump(123);exit;
    // 配置（替换成自己的）
    $Appid = "TP2nAqAVRQ7CA7iwCbQZD1";
    $Appkey = "jENYldWIQy5NachBrZ0ZP8";
    $Mastersecret = "1bXdeL3g347IgFEuqmQ4a4";
//    $this->appId = 'TP2nAqAVRQ7CA7iwCbQZD1';
//    $this->appkey = 'jENYldWIQy5NachBrZ0ZP8';
//    $this->masterSecret = '1bXdeL3g347IgFEuqmQ4a4';
// 实例化
    $gpush = new hhGpush($Appid,$Appkey,$Mastersecret);

// 发送推送
    $gpush->PushMsgToSingle([
        "clientid"=> "xxxxxxxxxx",
        "event"=> "warning",
        "title"=> "健康告知",
        "content"=> "您的中二病已经很严重了！",
        "push"=> "smart",
        "system"=> "android",
        "silent"=> false
    ]);

// 打印结果
    var_dump($gpush->result);
}


}
