<?php
namespace common\assets;

use yii\web\AssetBundle;

/**
 * AdminLTE Plugins
 * Assets for AdminLTE plugins are not included in our AdminLteAsset but you can find these files in your vendor directory under vendor/almasaeed2010/adminlte/plugins.
 * So if you want to use any of them we recommend to create a custom bundle where you list the plugin files you need:
 */
class AdminLtePluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';
    public $js = [
        'datatables/dataTables.bootstrap.min.js',
        // more plugin Js here
    ];
    public $css = [
        'datatables/dataTables.bootstrap.css',
        "https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"
        // more plugin CSS here
    ];
    public $depends = [
        'dmstr\web\AdminLteAsset',
    ];
}
