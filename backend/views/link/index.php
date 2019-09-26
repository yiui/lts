<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use dosamigos\datepicker\DateRangePicker;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\bootstrap\ButtonDropdown;
use common\assets\LayerAsset;
use kartik\grid\GridView;//引入自定义网格系统(只能选一)
use kartik\export\ExportMenu;//导出扩展
LayerAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel backend\models\LinkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Links');
$this->params['breadcrumbs'][] = $this->title;
$action = \backend\widgets\NumStatusActionWidgets::widget(['status' => \common\models\Link::STATUS_ARRAY, 'k' => 7]);
Modal::begin([
    'id' => 'view-modal',
    'header' => '<h4 class="modal-title">查看</h4>',
]);
Modal::end();

$viewJs = <<<JS
$('.data-view').on('click', function () {
    $.get('viewajax', { id: $(this).closest('tr').data('key') },
        function (data) {
            $('.modal-body').html(data);
        }
    );
});

$('.data-status').on('click', function(){
    var data={ id: $(this).data('id') };
    var status=$(this).data('status');
        $.post('status', data, function (msg) {
           if(msg.code == 200) {
               layer.load(1);
                location.reload();
                layer.close(index);
            }else{
             
            }
        }, 'json');
});
    

$('select').attr('style','width:80px');
JS;

$export= ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
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
    //'encoding' => 'GBK',//UTF-8
    'dropdownOptions' => [
        'label' => '导出',
        'class' => 'btn btn-default'
    ],
    'exportConfig' => [
        ExportMenu::FORMAT_TEXT => false,
        ExportMenu::FORMAT_PDF => false,
        ExportMenu::FORMAT_EXCEL_X => FALSE,
    ],
    'columnSelectorOptions'=>[
        'label' => '选择字段',
    ],
    'filename' => '友情链接列表_'.date('Y-m-d'),
    'selectedColumns'=> [1,2,3,4], // 默认选定导出字段
    'hiddenColumns'=>[0, 5], // 隐藏#和操作栏
]);



?>
    <div class="link-index">

        <h1><?= Html::encode($this->title) ?></h1>
        <p>
            <?= Html::a(Yii::t('backend', 'Create Link'), ['create'], ['class' => 'btn btn-success']) ?>
            <?= $action ?>
            <?=$export ?>
        </p>

        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'filterSelector' => "select[name='".$dataProvider->getPagination()->pageSizeParam."'],input[name='".$dataProvider->getPagination()->pageParam."']",
            'pager' => [
                'class' => \liyunfang\pager\LinkPager::className(),
                'template' => '{pageButtons} {customPage} {pageSize}', //分页栏布局
                'pageSizeList' => [10, 20, 30, 50,100,150], //页大小下拉框值
                'customPageWidth' => 50,            //自定义跳转文本框宽度
                'customPageBefore' => '<br/>跳转到第 ',
                'customPageAfter' => ' 页</p>  ',
            ],
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'responsiveWrap' => false,
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
                [
                    'label'=>'操作',
                    'value' => function($model, $key) {

                        return ButtonDropdown::widget([
                            'label' => '操作',
                            'options' => [
                                'class' => 'btn-info btn-xs'
                            ],
                            'dropdown' => [
                                'items' => [
                                    [
                                        'label' => '查看',
                                        'url' => '#',
                                        'options'=>[
                                            'data-toggle' => 'modal',
                                            'data-target' => '#view-modal',
                                            'class' => 'data-view',
                                            'data-id' => $key,
                                        ]
                                    ],
                                    [
                                        'label' => '编辑',
                                        'url' => '#',
                                        'options'=>[
                                            'data-toggle' => 'modal',
                                            'data-target' => '#view-modal',
                                            'class' => 'data-update',
                                            'data-id' => $key,
                                        ]
                                    ],
                                    [
                                        'label' => '状态',
                                        'url' => '#',
                                        'options'=>[
                                            'class' => 'data-status',
                                            'data-id' => $key,
                                            'data-status' => $model->status,
                                        ]
                                    ],

                                ],
                            ],
                        ]);
                    },
                    'format'=>'raw',
                    'headerOptions' => ['width' => '70px'],
                ],
                'id',
                'title',
                'url:url',
                'status',
                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        if ($model->status == 1) {
                            return html::button('<i class="fa fa-check"></i>', ['class' => 'data-status btn btn-xs btn-success', 'data-id' => $model->id, 'data-status' => $model->status]);
                        } else {
                            return html::button('<i class="fa fa-close"></i>', ['class' => 'data-status btn btn-xs btn-danger', 'data-id' => $model->id, 'data-status' => $model->status]);
                        }
                    },
                    'format' => 'raw',
                    'filter' => Html::activeDropDownList($searchModel, 'status', \common\models\Link::STATUS_ARRAY, ['prompt' => '全部', 'class' => 'form-control']),
                ],
                [
                    'attribute' => 'created_at',
                    'value' =>
                        function ($model) {
                            return date('Y-m-d H:i:s', $model->created_at);   //主要通过此种方式实现
                        },
                    //检索框小一点吧，比较好看一点
                    'headerOptions' => [
                        'class' => 'col-md-2'
                    ],
                    //检索条件
                    'filter' => DateRangePicker::widget([
                        'name' => 'LinkSearch[date_from]',
                        'value' => $searchModel->date_from,
                        'nameTo' => 'LinkSearch[date_to]',
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

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{audi} {view} {update} {delete}',
                    'buttons' => [
                        //动作的URL，当前模型，数据提供者中模型的键
                        'audi' => function ($url, $model, $key) {
                            //参数
                            $options = [
                                'title' => Yii::t('yii', '立刻审核'),
                                'aria-label' => Yii::t('yii', '立刻审核'),
                                'data-method' => 'post',
                            ];

                            //返回动作按钮
                            return Html::a('<span class="glyphicon glyphicon-saved"></span>', ['audi', 'id' => $model->id], $options);
                        },
                    ],//按钮渲染回调函数数组，键是按钮名（action），值为需要处理按钮的函数：funtion($url,$model,$key){}，需要修改哪个就增加哪个，其他的默认页处理过了
                ],
            ],
        ]); ?><?php Pjax::end(); ?>
    </div>
<?= $action;
echo \backend\widgets\SetNumStatusWidgets::widget(['view' => $this]);
$this->registerJs($viewJs, \yii\web\View::POS_END);