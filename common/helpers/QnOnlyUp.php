<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/8/31
 * Time: 10:31
 */
namespace common\helpers;

use yii\base\Model;

class QnOnlyUp {
    //七牛
    public $qiniu_ak='JZJKXndJcI6YLUzU2NEtoeRTislRk9gJa39WDnzC';
    public $qiniu_sk='AphYg7PmHh4mzjZ7zo8ZrM9Shbky9wyqmUeejwpk';
    public $qiniu_bucket='cdns';

    public $config;//配置

    public $file;//文件
    public $key;//资源名,如果上传策略中 scope 指定为：<bucket>:<key>， 则该字段也必须指定。
    public $x;//自定义变量，必须以 x: 开头，不限个数。里面的内容将在 callbackBody 参数中的 $(x:custom_name) 求值时使用。
    public $token;//token 上传凭证
    //public $crc32;//上传内容的 crc32 校验码。如填入，则七牛服务器会使用此值进行内容检验。
    public $accept;//当 HTTP 请求指定 accept 头部时，七牛会返回 content-type 头部的值。该值用于兼容低版本 IE 浏览器行为。低版本 IE 浏览器在表单上传时，返回 application/json 表示下载，返回 text/plain 才会显示返回内容

    function __construct(array $config)
    {
        $default_config=[
            'bucket'=>$this->qiniu_bucket,
            'key'=>'',
            'x'=>'',
        ];

        $this->config=array_merge($default_config,$config);
    }

    public function upHtml(){
        print_r($this->file);
    }


}