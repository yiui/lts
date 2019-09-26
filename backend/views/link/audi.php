<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Link */

$this->title = '综合信息'.$model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Link'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="car-gold-view">

        <p   style="display:inline;">
           <?php
            //参数说明：
            //model 当前模型，必须；view 当前视图，需要审核时需要；ckinfo 是否需要保存到ckinfo表中；
            //status 需要设置的 状态数值=>状态名称 数组，状态表中状态已经有异常和冻结，如果需要其他状态请加入，非状态表中的状态，默认为空即不自动加上状态设置，如果需要请手动加上此数组
            echo \backend\widgets\AudiWidgets::widget(['model'=>$model,'view'=>$this]);
            ?>
        </p>
        <?php
        Pjax::begin([
            'id'=>'info_pj',
            'enablePushState'=>false,
            'enableReplaceState'=>false,
            'timeout'=>10000,
            'clientOptions'=>[
                'type'=>'GET',
                'container'=>'#main_info',
            ]
        ]);
        echo Tabs::widget([
            'id' => 'tabsxxxx',
            'items'=>[
                [
                    'label'=>'详情',
                    'url'=>Url::to(['/link/audi','id'=>$model->id]),
                    'headerOptions' => ['onclick'=>'addloding(this)'],
                ],
                [
                    'label'=>'轮播图片',
                    'url'=>Url::to(['/link/audi-view','id'=>$model->id]),
                    'headerOptions' => ['onclick'=>'addloding(this)'],
                ],
            ]
        ]);
        Pjax::end();
        ?>
        <div id="main_info">
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




    </div>
<?php
$cdn=Yii::$app->params['web_cdn'];
$js1 = <<<JS
             function addloding(obj) {
      ($(obj).siblings()).each(function() {
        $(this).attr('class',null);
      });
      $(obj).addClass('active');
      $("#main_info").html("<center><img src='$cdn/common/img/huang.gif'><br>正在加载...</center>");
    }
JS;
$this->registerJs($js1,\yii\web\View::POS_END);
?>