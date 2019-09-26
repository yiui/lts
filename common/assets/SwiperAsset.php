<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class SwiperAsset extends AssetBundle
{
//    public $sourcePath = '@static/swiper/';
    public $sourcePath=null;
    public $basePath=null;
    public $baseUrl=null;

    public $css = [
        'swiper.min.css'
    ];
    public $js = [
        'swiper.min.js',
    ];
    public $depends = [
    ];

    public $jsOptions = [
        'position'=>View::POS_END,
    ];

    public function init()
    {
        $this->baseUrl=\Yii::$app->params['web_cdn'].'swiper';
    }
}
