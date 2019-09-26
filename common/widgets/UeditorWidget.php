<?php
namespace common\widgets;

use common\assets\UeditorAsset;
use yii\base\Widget;
use yii\helpers\Url;
use yii\web\View;

/**
 * 注册并使用百度编辑器
 * $view 必须
 * $name 编辑器ID名
 * Class UeditorWidget
 * @package common\widgets
 */
class UeditorWidget extends Widget {
	//定义属性
	public $id;//ID名
	public $view;//必须，当前视图对象

	//重写初始化
	public function init(){
		parent::init();
		//如果不需要初始化，就不必重写了
	}
	
	//吃些运行方法，此方法返回小部件html代码或可直接输出代码
	public function run(){
	    UeditorAsset::register($this->view);//注册编辑器

        if (empty($this->id)){
            $this->id='editor';
        }
//        $cdn=\Yii::$app->params['web_cdn'];
        $cdn=\Yii::$app->urlManager->hostInfo.'/';//注册当前web URL
        $up=\Yii::$app->urlManager->createAbsoluteUrl(['ueditor/up']);//创建绝对路径
        $js=<<<MY_JS
window.UEDITOR_UP_URL= "$up";
window.UEDITOR_HOME_URL = "$cdn"+"ueditor/";
MY_JS;

        $this->view->registerJs($js, View::POS_HEAD);//注册常用变量
        $time=time();
        $js=<<<MY_JS
var ue$time = UE.getEditor('$this->id');
MY_JS;

        $this->view->registerJs($js, View::POS_READY);//运行编辑器
//        $this->view->registerJs($js, View::POS_END);//运行编辑器
	}
}