<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Link */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="link-view">

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
        <?= \backend\widgets\NumStatusActionWidgets::widget(['status' => \common\models\Link::STATUS_ARRAY, 'id' => $model->id]) ?>
    </p>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">个人详情</a></li>
        <li><a href="#tab2" data-toggle="tab">面试评价</a></li>
        <li><a href="#tab3" data-toggle="tab">整体评价</a></li>
        <li><a href="#tab4" data-toggle="tab">皓石经历</a></li>
    </ul>
    <div class="tab-content" style="padding:15px;">
        <div id="tab1" class="tab-pane active">
            tab测试
            q2e3rfdaswref
        </div>
        <div id="tab2" class="tab-pane">
            2134135
        </div>
        <div id="tab3" class="tab-pane">
            341252345
        </div>
        <div id="tab4" class="tab-pane">
            <ul style="padding:15px;">
                <div>12431243</div>
            </ul>
        </div>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'url:url',
            'created_at',
            [
                    'attribute'=>'status',
                'value'=>function($model){
        return \common\models\Link::STATUS_ARRAY[$model->status];
                }
            ],
        ],
    ]) ?>

</div>
