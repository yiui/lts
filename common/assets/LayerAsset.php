<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class LayerAsset extends AssetBundle
{
    public $sourcePath=null;
    public $basePath=null;
    public $baseUrl=null;

    public $css = [
    ];
    public $js = [
        'layer/layer.js',
        'dialog.js'
    ];
//    public $jsOptions = [
//        'position'=>View::POS_END,
//    ];

    public function init()
    {
        $this->baseUrl=\Yii::$app->params['web_cdn'].'common/js';
    }
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
