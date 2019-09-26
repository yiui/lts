<?php
namespace common\widgets;

use common\models\Notify;
use common\models\NotifyGet;
use common\models\NotifyUser;
use yii;
use yii\base\Widget;
use yii\helpers\Url;

/**
 * 注册并使用百度编辑器
 * $view 必须
 * $name 编辑器ID名
 * Class UeditorWidget
 * @package common\widgets
 */
class MsgWidget extends Widget {
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

        $new_msg_num=NotifyGet::getMessageNum();//新消息数目
        $str='
<li class="dropdown messages-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">'.($new_msg_num>0?$new_msg_num:'').'</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">'.($new_msg_num > 0 ? '你有'.$new_msg_num.'个新私信':'暂没有新私信').'</li>
        <li>
            <ul class="menu">
        ';

        $dependency=new \yii\caching\DbDependency(['sql'=>'SELECT `created_at` FROM `'.NotifyUser::getTableSchema()->name.'` WHERE `type`='.Notify::SX_TYPE.' AND `user_id`=:uid ORDER BY `created_at` DESC LIMIT 1','params'=>[':uid'=>Yii::$app->user->id]]);
        if ($this->view->beginCache('common.notify.msg.head.'.Yii::$app->user->id,['cache'=>'fileCache','enabled'=>Yii::$app->params['frag_cache'],'duration' => 180,'dependency' => $dependency])) {

            foreach (NotifyGet::getMsgArr() as $notify) {
                $str .= '
                <li>
                    <a href="' . Yii::$app->params['user_domain'] . Url::to(['/msg/' . $notify['id'] ]).'">
                        <div class="pull-left">
                            <img class="img-circle" alt="'.$notify['username'].'" src="'.$notify['pic'].'">
                        </div>
                        <h4>
                            ' . $notify['username'] . '
                            <small><i class="fa fa-clock-o"></i> ' . $notify['time'] . '</small>
                        </h4>
                        <p>' . $notify['msg'] . '</p>
                    </a>
                </li>
                ';
            }

            $this->view->endCache();
        }

        $str.='
            </ul>
        </li>
        <li class="footer"><a href="' . Yii::$app->params['user_domain'] .Url::to(['/msg/index']).'">查看所有信息</a></li>
    </ul>
</li>
        ';

        return $str;
    }
}