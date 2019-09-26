<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/7/12
 * Time: 14:54
 */
namespace backend\widgets;

use common\assets\JishiAsset;
use yii;
use yii\base\Widget;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Status;
use yii\bootstrap\Modal;

class AudiWidgets extends Widget {
    //定义属性
    public $model;//当前模型，必须
    public $view;//当前视图，需要记录到ckinfo时需要

    //如果是数字状态，应该传入需要审核人员设置的状态数组，包含状态数值=>状态名的数组，完全按照此数组，不传则只有激活状态设置
    //如果是表中状态，此项被看作另外需要设置的状态，不传则只有激活状态和异常、冻结状态设置
    public $status=null;

    public $ckinfo=true;//是否有需要记录到ckinfo的

//    //重写初始化
//    public function init(){
//        parent::init();
//        //如果不需要初始化，就不必重写了
//    }

    //运行方法，此方法返回小部件html代码或可直接输出代码
    public function run(){
        if (empty($this->model)){
            return null;
        }

        $action='';

        if ($this->ckinfo) {
            $target = ($this->model)::getTableSchema()->name;//给audi使用的目标表名
            $target_id = $this->model->id;//给audi使用的目标ID
            $url = Url::to(['audi/add', 'target' => $target, 'target_id' => $target_id, 'status_id' => '']);//audi请求的URL
        }

        $status=null;//激活状态名
        $k=null;//激活状态ID值

        if (isset($this->model->status_id)){
            $all=Status::all(($this->model)::getTableSchema()->name);

            //给激活状态使用
            if (is_array($all) and defined(($this->model)::className().'::STATUS_ACTIVE')) {
                $k = array_search(($this->model)::STATUS_ACTIVE, $all);
                if ($k!==false and $this->model->status_id!=$k) {
                    $status=($this->model)::STATUS_ACTIVE;
                }
            }

            //异常
            if (($eid=array_search(Status::STATUS_ERROR,$all))!==false){
                if ($this->ckinfo){
                    $action.='
<button title="'.Status::STATUS_ERROR.'" onclick="audi('.$eid.');jishi(5,this,\''.Status::STATUS_ERROR.'\',1)" data-toggle="modal" data-target="#audi-modal" class="btn btn-danger">
    <span class="glyphicon glyphicon-warning-sign" aria-hidden="true" title="'.Status::STATUS_ERROR.'"></span>
    <span>'.Status::STATUS_ERROR.'</span>
</button> ';
                }else{
                    $action .= Html::a('设为'.Status::STATUS_ERROR, ['set-one', 'id' => $this->model->id, 'value' => $eid], [
                            'class' => 'btn btn-success',
                            'data' => [
                                'confirm' => '确定把状态设置为' . Status::STATUS_ERROR . '吗？',
                                'method' => 'post',
                            ],
                            'onclick'=>'jishi(5,this,\''.Status::STATUS_ERROR.'\',1)'
                        ]) . ' ';
                }
            }

            //冻结
            if (($fid=array_search(Status::STATUS_FREEZE,$all))!==false){
                if ($this->ckinfo){
                    $action.='
<button title="'.Status::STATUS_FREEZE.'" onclick="audi('.$fid.');jishi(5,this,\''.Status::STATUS_FREEZE.'\',2)" data-toggle="modal" data-target="#audi-modal" class="btn btn-danger">
    <span class="glyphicon glyphicon-warning-sign" aria-hidden="true" title="'.Status::STATUS_FREEZE.'"></span>
    <span>'.Status::STATUS_FREEZE.'</span>
</button> ';
                }else{
                    $action .= Html::a('设为'.Status::STATUS_FREEZE, ['set-one', 'id' => $this->model->id, 'value' => $fid], [
                            'class' => 'btn btn-success',
                            'data' => [
                                'confirm' => '确定把状态设置为' . Status::STATUS_FREEZE . '吗？',
                                'method' => 'post',
                            ],
                            'onclick'=>'jishi(5,this,\''.Status::STATUS_FREEZE.'\',2)'
                        ]) . ' ';
                }
            }
        }else if (isset($this->model->status)){
            //给激活状态使用
            if (defined(($this->model)::className().'::STATUS_ARRAY') and defined(($this->model)::className().'::STATUS_ACTIVE') and isset(($this->model)::STATUS_ARRAY[($this->model)::STATUS_ACTIVE])){
                $k = array_search(($this->model)::STATUS_ACTIVE, ($this->model)::STATUS_ARRAY);
                if ($k!==false and $this->model->status!=$k) {
                    $status=($this->model)::STATUS_ARRAY[($this->model)::STATUS_ACTIVE];
                }
            }
        }

        //看看有没有传入需要设置的状态，此状态需要记录到 ckinfo
        if ($this->status and is_array($this->status)){
            if ($this->ckinfo){
                foreach ($this->status as $i => $v) {
                    $action.='
<button title="'.$v.'" onclick="audi('.$i.');jishi(5,this,\''.$v.'\','.($i+3).')" data-toggle="modal" data-target="#audi-modal" class="btn btn-danger">
    <span class="glyphicon glyphicon-warning-sign" aria-hidden="true" title="'.$v.'"></span>
    <span>'.$v.'</span>
</button> ';
                }
            }else {
                foreach ($this->status as $i => $v) {
                    $action .= Html::a('设为' . $v, ['set-one', 'id' => $this->model->id, 'value' => $i], [
                            'class' => 'btn btn-success',
                            'data' => [
                                'confirm' => '确定把状态设置为' . $v . '吗？',
                                'method' => 'post',
                            ],
                            'onclick'=>'jishi(5,this,\''.$v.'\',,'.($i+3).')'
                        ]) . ' ';
                }
            }
        }

        if ($status and $k){
            //激活状态
            $action .= Html::a('设为'.$status, ['set-one', 'id' => $this->model->id, 'value' => $k], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'confirm' => '确定把状态设置为' . $status . '吗？',
                        'method' => 'post',
                        'onclick'=>'jishi(5,this,\''.$status.'\','.($k+100).')'
                    ],
                ]);
        }

        if ($this->ckinfo){
            Modal::begin([
                'id' => 'audi-modal',
                'header' => '<h4 class="modal-title" style="color: red;">正在审核...</h4>',
                'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭窗口</a>',
            ]);
            Modal::end();

            $js=<<<MY_JS
function audi(status_id){
    $.get('$url'+status_id,function (data, status) {
        if (status == "success")
            $('#audi-modal .modal-body').html(data);
        if (status == "error")
            $('#audi-modal .modal-body').html('请求失败，请重试！');
    });
}
MY_JS;
            $this->view->registerJs($js,\yii\web\View::POS_END);
        }

        JishiAsset::register($this->view);
        return $action;
    }
}