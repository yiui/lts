<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * 将html代码效果转成图片，对某些js效果可能兼容性不是太好
 *
 * 如：         要转换的标签
 * html2canvas(document.body).then(function(canvas) {
    document.body.appendChild(canvas);//画到哪里，这里就是一张图片
    });
 *
 */
class UeditorAsset extends AssetBundle
{
//    public $basePath = '@webroot/ueditor';
//    public $baseUrl = '@web/ueditor';
    //public $sourcePath = '@static/ueditor';

    public $sourcePath=null;
    public $basePath=null;
    public $baseUrl=null;

    public $css = [
    ];

    public $js = [
        'ueditor.config.js',//可使用URL
        'ueditor.all.min.js',//可使用URL
        'lang/zh-cn/zh-cn.js',//可使用URL
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

//    public function init()
//    {
//        parent::init();
//    }

    public $jsOptions = [
        'position'=>View::POS_END,
    ];

    public function init()
    {
        $this->baseUrl=\Yii::$app->urlManager->hostInfo.'/ueditor';
//        $this->baseUrl=\Yii::$app->params['web_cdn'].'ueditor';
    }
}
