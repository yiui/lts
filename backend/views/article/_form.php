<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>


    <?php
    if (!empty($model->pic_id)) {
        echo '<img src="' . Yii::$app->params['web_cdn'] . $model->pic2->path . '" alt="' . $model->title . ' 的封面">';
    }
    echo $form->field($model, 'file')->fileInput(['class' => 'form-control'])->hint('上传一个图片作为文章封面');
    ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'content')->widget('kucha\ueditor\UEditor', []); ?>

    <?= $form->field($model, 'read_num')->textInput() ?>

    <?= $form->field($model, 'sourse')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'cate_id')->dropDownList(\common\models\Article::all()) ?>

    <?= $form->field($model, 'good_num')->textInput() ?>

    <?= $form->field($model, 'bads_num')->textInput() ?>
    <?= $form->field($model, 'status')->radioList(\common\models\Article::STATUS_ARRAY) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
