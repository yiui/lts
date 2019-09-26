<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use yii;
use yii\base\Action;
use common\models\JqUpload;

class JqDelAction extends Action
{
    /**
     * @param $date {"t":"表名，-隔开，如 car-new-exte-pic","id":目标ID 13}
     * @return array
     */
    public function run($id=null){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->request->isPost){
            return ['error'=>'无效的操作方式！'];
        }

        if (!empty($id) and !$id=Yii::$app->request->post('fid')){
            return ['error'=>'请先选择文件！'];
        }

        //删除图片，根据关联关系对应的数据会自动删除会将pic_id替换为空
        if (JqUpload::del(Yii::$app->request->post('fid'))){
            return ['success' => true];
        }

        return ['error' => '文件删除失败！文件不存在或没有权限删除！'];
    }
}