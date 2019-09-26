<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class StepAsset extends AssetBundle
{
//    public $sourcePath = '@static/common';
    public $sourcePath=null;
    public $basePath=null;
    public $baseUrl=null;

    public $css = [
        'css/step.css',
    ];
    public $js = [
        'js/step.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $jsOptions = [
        'position'=>View::POS_END,
    ];

    public function init()
    {
        $this->baseUrl=\Yii::$app->params['web_cdn'].'common';
    }
}
