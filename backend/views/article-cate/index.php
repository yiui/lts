<?php

use yii\helpers\Html;
//use yii\grid\GridView;//引入自带网格系统
use common\models\ArticleCate;
use nickdenry\grid\toggle\components\RoundSwitchColumn;
use kartik\grid\GridView;//引入自定义网格系统(只能选一)
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleCateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$zero=range(1,100,1);
$interview_arr = array_combine($zero,$zero);
$this->title = Yii::t('backend', 'Article Cates');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-cate-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('backend', 'Create Article Cate'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        // 'moduleId'=>'gridviewKrajee',//更改模块标识符以使用相应模块的设置
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id' => 'grid',
        // 'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],//鼠标经过高亮显示
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'kartik\grid\SerialColumn'],
            'id',
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'name',
                'editableOptions' => [
                    'placement' => 'left',
                    'header' => Yii::t('backend', 'Name'),
                    'inputType' => 'textArea',
                    'size' => 'md'
                ],
                'contentOptions' => ['style'=>'text-align:center'],//居中
                'vAlign' => 'middle',
               // 'width' => '80px',
                'format' => 'raw',
                ],
            [
                'attribute' => 'status',
                'value' => function ($model, $key) {
                    if ($model->status == 0) {
                        $status = Html::a('禁用', ['status', 'id' => $key], ['class' => 'btn btn-xs btn-danger']);
                    } else {
                        $status = Html::a('启用', ['status', 'id' => $key], ['class' => 'btn btn-xs btn-success']);
                    }
                    //$delete = Html::a('删除', ['delete', 'id' => $key], ['class' => 'btn btn-xs btn-info', 'data-confirm' => '确认删除吗']);
                    // return "$status $delete";
                    return "$status";
                },
                'contentOptions' => ['style'=>'text-align:center'],//居中
                'format' => 'raw',
                // 'headerOptions' => ['width' => 120],
               // 'width' => '100px',
                'filter' => Html::activeDropDownList($searchModel,
                    'status', \common\models\ArticleCate::STATUS_ARRAY,
                    ['prompt' => '全部', 'class' => 'form-control']
                ),
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'sort',
                'editableOptions' => [
                    'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                    'data' => ['' => ''] + $interview_arr,
                ],
                'contentOptions' => ['style'=>'text-align:center'],//居中
                'filter' => $interview_arr,
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
