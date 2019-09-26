<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/9/6
 * Time: 21:30
 */
namespace common\components\Qiniu;

use function Qiniu\base64_urlSafeEncode;
use Qiniu\Storage\UploadManager;

class Up extends Common {
    public $uploadMgr;

    /**
     * 获取鉴权 和 上传管理组件
     * up constructor.
     * @param $bucket
     */
    function __construct()
    {
        parent::__construct();
        $this->uploadMgr = new UploadManager();
    }

    /**
     * 获取上传token
     * @param null $key 上传到七牛后保存的文件名，不为空则只允许用户上传指定 key 的文件（前端key必须和此同名）。
     *                  在key不为空时文件默认允许修改（ insertOnly 属性值设为 1不允许修改，只能新增），若已存在同名资源则会被覆盖。
     *                  （isPrefixalScope=1 时key为文件名前缀，表示只能上传此前缀的key文件,上传的文件名必须以此key开头）
     *
     * @param null $bucket 上传到哪个空间里
     * @param null $notifyUrl 回调URL
     * @param $force
     * @return string
     */
    public function getToken($user_id=null,$key=null,$isPrefixalScope=0,$insertOnly=1,$returnUrl=null,$bucket=null,$notifyUrl = null,$force = true){
        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }

//        缩放
//        $persistentOps=DoImg::imageView2(2,287,192);
//        图片水印，多任务同时处理使用 | 隔开，多任务分别处理使用 ; 隔开
//        $persistentOps.='|'.DoImg::waterMarkImg();
//        文字水印
//        $persistentOps.='|'.DoImg::waterMarkText('哈哈哈').'|'.Common::saveas('x-$(key)');

//        $persistentOps.='|'.DoImg::imageMogr2([
//            'type'=>7,
//                'w'=>400,
//                'h'=>400,
//            ]).$this->saveas('ddddddddd');


        $policy = [
            //'scope'=>$bucket,//uploadToken()自动组合了
            'isPrefixalScope'=>$isPrefixalScope,//若为 1，表示允许用户上传以 scope 的 key 为前缀的文件。
            'endUser'=>base64_urlSafeEncode($user_id),//唯一属主标识
            //'deadline'=>3600,//上传凭证有效时间，uploadToken()自动加了
            'insertOnly'=>$insertOnly,//=1时，无论 scope 设置为什么形式,仅能以新增模式上传文件

            'returnUrl'=>$returnUrl,//浏览器端文件上传成功后，浏览器执行 303 跳转的 URL。如不设置 returnUrl，则直接将 returnBody 的内容返回给客户端。
            'returnBody'=>json_encode([//返回給上传端（在指定 returnUrl 时是携带在跳转路径参数中）的数据
                'bucket'=>'$(bucket)',
                'key'=>'$(key)',
                'fname'=>'$(fname)',
                'type'=>'$(mimeType)',
                'size'=>'$(fsize)',
                'w'=>'$(imageInfo.width)',
                'h'=>'$(imageInfo.height)',
                'hash'=>'$(etag)',
                'color'=>'$(exif.ColorSpace.val)',
                'persistentId'=>'$(persistentId)',//音视频转码持久化的进度查询ID。
            ]),

            'fsizeMin'=>10000,//限定上传文件大小最小值，单位：字节（Byte）
            'fsizeLimit'=>10000000,//限定上传文件大小最大值，单位：字节（Byte）
            'detectMime'=>0,//开启MimeType侦测功能。设为非 0 值，则忽略上传端传递的文件 MimeType 信息，使用七牛服务器侦测内容后的判断结果。
            'mimeLimit'=>'image/*',//限定用户上传的文件类型
            'fileType'=>0,//0 为普通存储（默认），1 为低频存储。

            'saveKey'=>$user_id?json_encode('user/$(year)/$(mon)/$(day)/$(endUser)-$(etag)-$(year)$(mon)$(day)$(hour)$(min)$(sec)$(ext)'):json_encode('common/$(year)/$(mon)/$(day)/$(etag)-$(year)$(mon)$(day)$(hour)$(min)$(sec)$(ext)'),//默认保存名 七牛使用hash

            //返回给服务器端的
            'callbackUrl' => $notifyUrl,//没有回调，将不执行，指定callbackUrl，必须指定callbackbody，且值不能为空
            'callbackBody'=>json_encode([
                'bucket'=>'$(bucket)',
                'key'=>'$(key)',
                'name'=>'$(fname)',
                'x:_csrf'=>'$(x:_csrf)',
                'type'=>'$(mimeType)',
                'size'=>'$(fsize)',
                'w'=>'$(imageInfo.width)',
                'h'=>'$(imageInfo.height)',
                'hash'=>'$(etag)',
                'color'=>'$(exif.ColorSpace.val)',
            ]),
            //'callbackBody' => '{"key":"$(key)","hash":"$(etag)","fsize":$(fsize),"bucket":"$(bucket)","name":"$(x:name),"_csrf":"$(x:_csrf)"}',//application/json
            //'callbackBody' => 'key=$(key)&hash=$(etag)&bucket=$(bucket)&fsize=$(fsize)&name=$(x:name)',//application/x-www-form-urlencoded格式
            'callbackBodyType' => 'application/json',//json格式应写上此项
            'callbackFetchKey'=>0,//是否启用fetchKey上传模式，0为关闭，1为启用。如果启用fetchKey上传模式，上传的key是由回调的结果指定的。
            //回调服务应对七牛云存储作出类似如下的响应：{    "key": <key>, "payload": <callback-json-object>},<key>的字段作为资源的名称存入存储中。<payload>部分返回给客户端。
            //例如，回调服务对七牛云存储作出如下的响应：{   "key": "sunflowerc.jpg",    "payload": { {"success":true,"name":"sunflowerc.jpg"} } }
            //则七牛将资源的key作为sunflowerc.jpg存入云存储中，返回给客户端的内容为：{"success":true,"name":"sunflowerc.jpg"}

//            'persistentOps'=>$persistentOps,//资源上传成功后触发执行的预转持久化处理指令列表。每个指令是一个 API 规格字符串，多个指令用;分隔。
//            'persistentNotifyUrl'=>$notifyUrl,//接收持久化处理结果通知的 URL
//            'persistentPipeline'=>self::PIPELINE,

            'deleteAfterDays'=>0,//过期天数，默认永久
        ];

        return $this->auth->uploadToken($bucket,$key,self::EXPIRES, $policy, $force);
    }

    //上传字符串
    public function str($str,$key=null){
        // 上传字符串到七牛
        list($ret, $err) = $this->uploadMgr->put($this->getToken(), $key, $str);
        echo "\n====> put result: \n";
        if ($err !== null) {
            var_dump($err);
        } else {
            var_dump($ret);
        }
    }

    /**
     * 上传文件
     * @param $key 上传到七牛后保存的文件名
     * @param $filePath 需要上传的文件的本地路径
     */
    public function upfile($filePath,$key){
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $this->uploadMgr->putFile($this->getToken(), $key, $filePath);
        echo "\n====> putFile result: \n";
        if ($err !== null) {
            var_dump($err);
        } else {
            var_dump($ret);
        }
    }

    /**
     * 上传视频和水印操作
     * @param $key
     * @param null $waterimg
     * @param null $bucket
     */
    public function upVdAndWater($key,$w=480,$h=360,$savekey=null,$waterimg=null,$bucket=null,$notifyUrl=null,$pipeline=null,$force=true){
        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }

        if (empty($pipeline)){
            $pipeline=self::PIPELINE;
        }

        if (empty($notifyUrl)){
            $notifyUrl=self::CALLBACK_URL;
        }
        if (empty($savekey)){
            $savekey='zm_wm_'.$w.'_'.$h.'_'.$savekey;
        }
        if (empty($waterimg)){
            $waterimg=$this->waterimg;
        }

        //        //带数据处理的凭证
//        $saveMp4Entry = \Qiniu\base64_urlSafeEncode($bucket . ":avthumb_test_target.mp4");
//        $saveJpgEntry = \Qiniu\base64_urlSafeEncode($bucket . ":vframe_test_target.jpg");
//        $avthumbMp4Fop = "avthumb/mp4|saveas/" . $saveMp4Entry;
//        $vframeJpgFop = "vframe/jpg/offset/1|saveas/" . $saveJpgEntry;
//        $policy = array(
//            'persistentOps' => $avthumbMp4Fop . ";" . $vframeJpgFop,
//            'persistentPipeline' => "video-pipe",
//            'persistentNotifyUrl' => "http://api.example.com/qiniu/pfop/notify",
//        );


        //上传视频，上传完成后进行m3u8的转码， 并给视频打水印
        $wmImg = \Qiniu\base64_urlSafeEncode($waterimg);
        $pfop = "avthumb/m3u8/wmImage/$wmImg";

        $policy = array(
            'persistentOps' => $pfop,
            'persistentNotifyUrl' => $notifyUrl,
            'persistentPipeline' => $pipeline  //独立的转码队列：https://portal.qiniu.com/mps/pipeline
        );
        $token = $this->auth->uploadToken($bucket, $key, self::EXPIRES, $policy, $force);

        list($ret, $err) = $this->uploadMgr->putFile($token, $savekey, $key);
        echo "\n====> putFile result: \n";
        if ($err !== null) {
            var_dump($err);
        } else {
            var_dump($ret);
        }
    }
}