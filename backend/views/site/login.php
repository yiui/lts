<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = '欢迎来到后台管理中心，请登录';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>
<script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
<div class="login-box">
    <div class="login-logo">
        <a href="#">后台管理中心</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">欢迎登录，请输入用户名和密码登陆。</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'username', $fieldOptions1)
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form
            ->field($model, 'password', $fieldOptions2)
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('登陆', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>

        <?php ActiveForm::end(); ?>

<!--        <div>-->
<!--            <a href="" title="微信登陆" onclick="return wechat_login()"><img src="https://open.weixin.qq.com/zh_CN/htmledition/res/assets/res-design-download/icon64_appwx_logo.png"></a>-->
<!--        </div>-->
<!--        <div id="wechat_login"></div>-->

    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
<script>
    function wechat_login() {
        var obj = new WxLogin({
            id:"wechat_login",//第三方页面显示二维码的容器id
            appid: "",//应用唯一标识，在微信开放平台提交应用审核通过后获得
            scope: "snsapi_login",//应用授权作用域，拥有多个作用域用逗号（,）分隔，网页应用目前仅填写snsapi_login即可
            redirect_uri: "<?=urlencode(Yii::$app->urlManager->createAbsoluteUrl(['we-chat/login']))?>",//重定向地址，需要进行UrlEncode
            state: "_csrf:"+$('meta[name="csrf-token"]').attr("content"),//不是必须，用于保持请求和回调的状态，授权请求后原样带回给第三方。该参数可用于防止csrf攻击（跨站请求伪造攻击），建议第三方带上该参数，可设置为简单的随机数加session进行校验
            style: "white",//不是必须，样式，提供"black"、"white"可选，默认为黑色文字描述。
            href: ""//不是必须，自定义样式链接，第三方可根据实际需求覆盖默认样式。
        });
        return false;
    }

</script>