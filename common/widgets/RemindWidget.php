<?php
namespace common\widgets;

use common\models\Notify;
use common\models\NotifyGet;
use common\models\NotifyUser;
use yii\helpers\Url;
use common\models\NotifyReason;
use common\models\NotifySubscription;
use yii;
use yii\base\Widget;

/**
 * 注册并使用百度编辑器
 * $view 必须
 * $name 编辑器ID名
 * Class UeditorWidget
 * @package common\widgets
 */
class RemindWidget extends Widget {
    //定义属性
    public $view;

    //重写初始化
//    public function init(){
//        parent::init();
//        //如果不需要初始化，就不必重写了
//    }

    //吃些运行方法，此方法返回小部件html代码或可直接输出代码
    public function run(){
        if (Yii::$app->user->isGuest){
            return null;
        }

        $new_red_num=NotifyGet::getRemindNum();//新提醒数目
        $str='
<li class="dropdown notifications-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning">'.($new_red_num>0?$new_red_num:'').'</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">'.($new_red_num > 0 ? '你有'.$new_red_num.'个新提醒':'暂没有新提醒').'</li>
        <li>
            <!-- inner menu: contains the actual data -->
            <ul class="menu">
        ';

        $dependency=new \yii\caching\DbDependency(['sql'=>'SELECT `created_at` FROM `'.NotifyUser::getTableSchema()->name.'` WHERE `type`='.Notify::TX_TYPE.' AND `user_id`=:uid ORDER BY `created_at` DESC LIMIT 1','params'=>[':uid'=>Yii::$app->user->id]]);
        if ($this->view->beginCache('common.notify.remind.head.'.Yii::$app->user->id,['cache'=>'fileCache','enabled'=>Yii::$app->params['frag_cache'],'duration' => 180,'dependency' => $dependency])) {

            $msgs=NotifyGet::getRemindArr(null,20,true);
            foreach ($msgs as $msg){

                $str.='<li><a href="'.Yii::$app->params['user_domain'].Url::to(['/msg/' . $msg['id'] ]).'" title="点击阅读"><i class="fa '.NotifyGet::getTag($msg['tb'],$msg['pri']).'"></i> '.$msg['msg'].'</a></li>';
            }

            $this->view->endCache();
        }

        $str.='
            </ul>
        </li>
        <li class="footer"><a href="' . Yii::$app->params['web_domain'] . Url::to(['/msg/index']).'">查看所有提醒</a></li>
    </ul>
</li>
        ';

        return $str;
    }
}