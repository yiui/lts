<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Article */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="article-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= \backend\widgets\NumStatusActionWidgets::widget(['status' => \common\models\Article::STATUS_ARRAY, 'id' => $model->id]) ?>
    </p>
    <?php
    if (!empty($model->pic_id)) {
        echo '<img src="' . Yii::$app->params['web_cdn'] . $model->pic2->path . '" alt="' . $model->title . ' 的封面">';
    }
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user->username;
                }
            ],
            'title',
            'description:ntext',
            // 'content:ntext',
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return date('Y-m-d H:i:s', $model->updated_at);
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return \common\models\Article::STATUS_ARRAY[$model->status];
                }
            ],
            'read_num',
            'sourse',
            'cate.name',
            'good_num',
            'bads_num',
        ],
    ]) ?>
</div>
<style>
    .article-content {
        height: auto;
        overflow: hidden;
        box-sizing: content-box;
        -moz-box-sizing: content-box; /* Firefox */
        -webkit-box-sizing: content-box; /* Safari */
        line-height: 2em;
        font-size: 1em;
        color: #000;
        font-weight: normal;
        text-indent: 0;
        word-wrap: break-word;
        position: relative;
        margin: auto;
        padding: 0;
        font-family: "Lucida Grande", Verdana, Lucida, Arial, Helvetica, "宋体", sans-serif;
    }

    .article-content p {
        text-indent: 2em;
    }

    .article-content p, .article-content span {
        text-indent: 0;
        color: #000;
        margin: auto;
        padding: 0;
        box-sizing: content-box;
        -moz-box-sizing: content-box; /* Firefox */
        -webkit-box-sizing: content-box; /* Safari */
        line-height: 1.5em;
        font-size: 1em;
        font-weight: normal;
        /*font-family:"Lucida Grande",Verdana,Lucida,Arial,Helvetica,"宋体",sans-serif;*/
    }
</style>
<p style="line-height: 40px; background-color: #aa573c; text-indent: 2em; color: #fff; font-weight: bold;">
    文章预览（共 <?= mb_strlen(\common\helpers\Str::purify($model->content)) ?>字/<?= mb_strlen($model->content) ?>字符）</p>
<div class="article-content">
    <?= \yii\helpers\HtmlPurifier::process($model->content) ?>
</div>