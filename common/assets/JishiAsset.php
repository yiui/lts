<?php
/**
 * 按钮点击之后倒计时功能
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/5/22 Time: 21:14
 */
namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main frontend application asset bundle.
 *
 * 使用方式：
 * 在目标按钮上加上 'onclick'=>'jishi(5,this,"提交")'
 *              jishi(时间秒，this 当前按钮,'按钮名称，默认空为 操作')
 * 插件上也类似加上此事件函数
 *
 * 最后注册本资源包到目标页面上
 */
class JishiAsset extends AssetBundle
{
    public $sourcePath=null;
    public $basePath=null;
    public $baseUrl=null;

//    public $css = [
//    ];
    public $js = [
        'js/jishi.js'
    ];
    public $jsOptions = [
        'position'=>View::POS_END,
    ];

    public function init()
    {
        $this->baseUrl=\Yii::$app->params['web_cdn'].'common';
    }
}