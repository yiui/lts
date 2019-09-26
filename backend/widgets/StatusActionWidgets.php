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

class StatusActionWidgets extends Widget {
    //定义属性
    public $tbname;//表名，通过表名获取状态，k 和 id 设置一个即可
    public $k=3;//多项操作时，必须设置，状态名时第几列？
    public $id;//具体某个ID的操作才设置

    public $del=true;//是否批量删除所选

    //重写初始化
    public function init(){
        parent::init();
        //如果不需要初始化，就不必重写了
    }

    //运行方法，此方法返回小部件html代码或可直接输出代码
    public function run(){
        //遍历出所有状态设置按钮，随意设置
        $action='';
        if ($this->tbname){
            if ($allstatus=Status::all($this->tbname)) {
                foreach ($allstatus as $k => $status) {
                    //更新状态设置功能时，这段判断语句就不需要了，自动设置状态值为状态ID，当是控制器里需要对ID进行判断和限制
                    if (empty($this->id)) {
                        $action .= Html::button($status . '所选', ['onClick' => 'setAll(' . $k . ',\''.$status.'\','. $this->k . ');jishi(5,this,\'' . $status . '\',1)', 'class' => 'btn btn-success']) . ' ';
                    } else {
                        $action .= Html::a('设为' . $status, ['set-one', 'id' => $this->id, 'value' => $k], [
                                'class' => 'btn btn-success',
                                'data' => [
                                    'confirm' => '确定把状态设置为' . $status . '吗？',
                                    'method' => 'post',
                                ],
                            ]) . ' ';
                    }
                }
            }
        }

        if (empty($this->id) and $this->del) {
            $action.=Html::button('批量删除',['onClick'=>'delAll();jishi(5,this,\'批量删除\',1)','class'=>'btn btn-success']).' ';
        }
        JishiAsset::register($this->view);

        return $action;
    }
}