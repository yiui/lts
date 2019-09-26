<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class LayuiAsset extends AssetBundle
{
    public $sourcePath=null;
    public $basePath=null;
    public $baseUrl=null;

    public $css = [
        'layui/css/layui.css',
    ];
    public $js = [
        'layui/layui.js',
    ];
    public $jsOptions = [
        'position'=>View::POS_END,
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
