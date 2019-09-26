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


class NumStatusActionWidgets extends Widget {
    //定义属性
    public $k=3;//多项操作时，必须设置，状态名时第几列？
    public $id;//具体某个ID的操作才设置

    //$status=[0=>'否',1=>'是']
    public $status;//直接设置可用状态，不通过表名获取，键位设置的状态值，值为状态表示的值

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
        if ($this->status){
            foreach ($this->status as $k => $status){
                if (empty($this->id)) {
                    $action .= Html::button($status . '所选', ['onClick' => 'setAll(' . $k . ',\'' . $status . '\',' . $this->k . ');jishi(5,this,\'' . $status . '\',1)', 'class' => 'btn btn-success']) . ' ';
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

        if (empty($this->id) and $this->del) {
            $action.=Html::button('批量删除',['onClick'=>'delAll();jishi(5,this,\'批量删除\',1)','class'=>'btn btn-success']).' ';
        }
        JishiAsset::register($this->view);

        return $action;
    }
}