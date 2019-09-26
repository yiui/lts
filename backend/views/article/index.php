<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Article;
use dosamigos\datepicker\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('backend', 'Articles');
$this->params['breadcrumbs'][] = $this->title;
$action = \backend\widgets\NumStatusActionWidgets::widget(['status' => \common\models\Article::STATUS_ARRAY, 'k' => 6]);
?>
    <div class="article-index">

        <h1><?= Html::encode($this->title) ?></h1>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a(Yii::t('backend', 'Create Article'), ['create'], ['class' => 'btn btn-success']) ?>
            <?= $action; ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'id' => 'grid',
            'columns' => [
                //  ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'name' => 'ids',
                    'headerOptions' => ['width' => 30],
                    //'footer' => '<button href="#" class="btn btn-default btn-xs btn-delete" url="'. Url::to(['del-all']) .'">删除</button>',
                    //'footerOptions' => ['colspan' => 5],  //设置删除按钮垮列显示；
                    //而且其他列的底部必须不显示哦，不然会多出来：增加此配置 'footerOptions' => ['class'=>'hide']
                ],
                'id',
                [
                    'label' => '用户',
                    'attribute' => 'user_id',
                    'value' => 'user.username',
                    'filter' => Html::activeTextInput($searchModel, 'username', ['class' => 'form-control']),
                ],
                [
                    'attribute' => 'title',
                    'value' => function ($model) {
                        return \common\helpers\Str::cut($model->title, 10);
                    }
                ],
                [
                    'attribute' => 'cate_id',
                    'value' => 'cate.name',
                    'filter' => Html::activeDropDownList($searchModel,
                        'cate_id', \common\models\Article::all(),
                        ['prompt' => '全部', 'class' => 'form-control']
                    )
                ],
//            'description:ntext',
//            'content:ntext',

                //'updated_at',
                //'read_num',
                //  'sourse',
                // 'face',

                // 'good_num',
                // 'bads_num',
                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return \common\models\Article::STATUS_ARRAY[$model->status];
                    },
                    'filter' => Html::activeDropDownList($searchModel,
                        'status', \common\models\Article::STATUS_ARRAY,
                        ['prompt' => '全部', 'class' => 'form-control']
                    )
                ],
                [
                    'attribute' => 'updated_at',
                    'value' =>
                        function ($model) {
                            return date('Y-m-d H:i:s', $model->updated_at);   //主要通过此种方式实现
                        },
                    'headerOptions' => [
                        'class' => 'col-md-2'
                    ],
                    //检索条件
                    'filter' => DateRangePicker::widget([
                        'name' => 'ArticleSearch[date_from]',
                        'value' => $searchModel->date_from,
                        'nameTo' => 'ArticleSearch[date_to]',
                        'valueTo' => $searchModel->date_to,
                        'language' => 'zh-CN',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                            'clearBtn' => true,
                            'keepEmptyValues' => true
                        ]
                    ])
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
<?php
echo $action;
//删除所选和设置状态JS代码插件
echo \backend\widgets\SetStatusWidgets::widget(['view' => $this]);
