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
class Html2canvasAsset extends AssetBundle
{
    public $sourcePath=null;
    public $basePath=null;
    public $baseUrl=null;

//    public $sourcePath = '@static/html2canvas/';
    public $css = [

    ];
    public $js = [
        'html2canvas.min.js',//可使用URL
        'html2canvas.svg.min.js',//可使用URL
    ];

//    //在资源包类中设置的选项会应用到该包中 每个 CSS/JavaScript 文件，
//    //如果想对每个文件使用不同的选项， 应创建不同的资源包并在每个包中使用一个选项集。
//
//    //只想IE9或更高的浏览器包含一个CSS文件，可以使用如下选项
//    //public $cssOptions = ['condition' => 'lte IE9'];
////<!--[if lte IE9]>
////<link rel="stylesheet" href="path/to/foo.css">
////<![endif]-->
//    //为链接标签包含<noscript>可使用如下代码：public $cssOptions = ['noscript' => true];
//
//    //为使JavaScript文件包含在页面head区域（JavaScript文件默认包含在body的结束处）使用以下选项：
//    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
//
//    //yii\web\AssetBundle::jsOptions: 当调用yii\web\View::registerJsFile()注册该包 每个 JavaScript文件时， 指定传递到该方法的选项。
//    //yii\web\AssetBundle::cssOptions: 当调用yii\web\View::registerCssFile()注册该包 每个 css文件时， 指定传递到该方法的选项。
//    //yii\web\AssetBundle::publishOptions: 当调用yii\web\AssetManager::publish()发布该包资源文件到Web目录时 指定传递到该方法的选项，仅在指定了yii\web\AssetBundle::sourcePath属性时使用。
//
//    /**
//     * @inheritdoc
//     */
//    public function init()
//    {
//        $action_id = \Yii::$app->controller->action->id ;
//        if ($action_id =='error') {
//            $this->depends[] = 'backend\assets\ErrorAsset';
//        } else {
//            $this->depends[] = 'backend\assets\CssJsAsset';
//        }
//
//        parent::init();
//    }

    public $jsOptions = [
        'position'=>View::POS_END,
    ];

    public function init()
    {
        $this->baseUrl=\Yii::$app->params['web_cdn'].'html2canvas';
    }
}
