<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use common\helpers\Str;
use common\models\Config;
use common\models\Pic;
use common\models\Status;
use common\models\User;
use yii;
use yii\helpers\FileHelper;
use yii\base\Action;
use Da\QrCode\QrCode;
use yii\web\Response;

class QrAction extends Action {
    public $logo=false;

    /**
     * 使用中，显示二维码
     * @param $url 创建URL时请注意，请创建绝对URL，以免外部访问不到：
     * Yii::$app->urlManager->createAbsoluteUrl(['article/read','id'=>$model->id])])
     * 以上必须加上控制器名，不然创建的URL不正确，且我们需要使用
     * 必须确保URL唯一且不会产生其他URL来访问某内容，如不要加上page等参数，只考虑操作的参数等
     * @param $uid 用户ID号，以此找到用户的头像放入二维码中
     * @return mixed
     */
    public function run($url,$uid=null){
        //参数可以为各种格式化的数据，这里使用的是字符串

        //判断来源，防盗链
        if (strpos(Yii::$app->request->referrer,Yii::$app->request->hostInfo)===0) {
            //是否是本站地址，防盗用
            $url=urldecode($url);
            if (strpos($url,'http')===0 and $url_arr=parse_url($url) and isset($url_arr['host'])){
                if (strpos(Yii::$app->request->serverName, explode('.', $url_arr['host'],2)[1])===false){
                    return '请勿盗用！欢迎访问：'.Yii::$app->name.' - '.Yii::$app->request->hostInfo;
                }
            }else{
                return '请求的URL不正确！';
            }

            $action=substr(urldecode($url),strlen(Yii::$app->request->hostInfo)+1);
            //echo Yii::$app->request->hostInfo.'<hr>'.urldecode($url).'<hr>'.$action;
            $action=explode('/',$action);

            //我们将第一个看作控制器名
            if (!empty($action[0]) and strpos($action[0],'.')===false) {
                //如果有控制器名的话，创建这个子目录保存二维码
                $path=Yii::getAlias('@static/qr/').$action[0].'/';
                if (!is_dir($path)){
                    FileHelper::createDirectory($path);
                }
            }else{
                $path=Yii::getAlias('@static/qr/others/');
            }

            $filename=$path.md5(($uid?$uid.'-':'').$url).'.png';

            Yii::$app->response->format = Response::FORMAT_RAW;
            Yii::$app->response->headers->add('Content-Type', 'image/png');
            Yii::$app->response->headers->add('Content-Type', 'binary');


            if (is_file($filename)){
                //$this->renderFile($filename);
                return Yii::$app->response->sendFile($filename);
            }else{
                $logo=null;//是否加入logo
                //参数可以为各种格式化的数据，这里使用的是字符串
                if ($this->logo or $uid){
                    if ($uid){
                        $pic_name=Pic::getTableSchema()->name;
                        $user_name=User::getTableSchema()->name;

                        $uimg=(new yii\db\Query())->select($pic_name.'.`path` AS img')->from(User::getTableSchema()->name)->innerJoin($pic_name,$user_name.'.`pic_id`='.$pic_name.'.`id`')->where($user_name.'.`status_id`='.Status::v2I($user_name,User::STATUS_ACTIVE).' AND '.$user_name.'.`id`='.$uid)->limit(1)->one();
                        if ($uimg and isset($uimg['img'])){
                            $logo=Config::STATIC_DIR_PATH.$uimg['img'];
                        }
                    }
                    if (!$logo and $this->logo){
                        $logo=Yii::getAlias($this->logo);
                    }
                }

                if ($logo){
                    //加上logo
                    $qrCode = (new QrCode($url))
                        ->useLogo($logo)
                        ->setLogoWidth(50)
                        ->setSize(250)
                        ->setMargin(5)
                        ->useForegroundColor(0, 0, 0);
                }else{
                    //不加logo
                    $qrCode = (new QrCode($url))
                        ->setSize(250)
                        ->setMargin(5)
                        ->useForegroundColor(0, 0, 0);
                }
                // 写入文件:
                //$qrCode->writeFile($filename); // writer defaults to PNG when none is specified
                //image/png

                // now we can display the qrcode in many ways
                return $qrCode->writeString();
            }
        }else{
            return '请勿盗链！欢迎访问：'.Yii::$app->name.' - '.Yii::$app->request->hostInfo;
        }
    }

}