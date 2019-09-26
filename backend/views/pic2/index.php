<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\Pic2Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Pic2s');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic2-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a(Yii::t('backend', 'Create Pic2'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'filterSelector' => "select[name='".$dataProvider->getPagination()->pageSizeParam."'],input[name='".$dataProvider->getPagination()->pageParam."']",
            'pager' => [
                'class' => \liyunfang\pager\LinkPager::className(),
                'template' => '{pageButtons} {customPage} {pageSize}', //分页栏布局
                'pageSizeList' => [10, 20, 30, 50,100,150], //页大小下拉框值
                'customPageWidth' => 50,            //自定义跳转文本框宽度
                'customPageBefore' => '<br/>跳转到第 ',
                'customPageAfter' => ' 页 ',
            ],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'path',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
