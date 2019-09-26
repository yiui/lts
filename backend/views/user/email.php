<?php

use yii\helpers\Html;
use common\assets\LayerAsset;
LayerAsset::register($this);
$Js=<<<JS
$("#send").click(function(){
    var email=$("#test-email").val();
        var content=$("#content").val();
    var url='send-email';
     $.post(url, {'email':email,'content':content}, function (data) {
    		if (data.code == 0) {
             layer.msg('发送成功');
    		}else{
             layer.msg(data.msg);
            }
    	}, 'json');
});
JS;
$this->registerJs($Js, \yii\web\View::POS_END);

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = '邮箱发送';
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="form-group field-user-username required has-success">
        <label class="control-label" for="email">内容<input type="text" id="content" class="form-control" placeholder="这里是测试内容" name="content"  maxlength="255" aria-required="true" ></label>
    </div>
<div class="form-group field-user-username required has-success">
    <label class="control-label" for="email">测试邮箱<input type="text" id="test-email" placeholder="请输入邮箱" class="form-control" name="email"  maxlength="128" aria-required="true" ></label>
</div>
<?php
echo Html::button('发送',['class' => 'btn btn-success btn-flat','id'=>'send']);
