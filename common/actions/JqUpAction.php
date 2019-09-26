<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use yii;
use yii\helpers\Url;
use yii\base\Action;
use common\models\JqUpload;
use yii\web\UploadedFile;

class JqUpAction extends Action
{
    public $config=[];
    public $options=['th_width'=>400,'th_height'=>400];//图片处理选项数组
    public $model='pic';//pic 使用 UploadImg,pic2 使用 UploadImg2, 其他使用 file

    /**
     * @param $date {"t":"目标表名","q":{"需要插入的字段1":"值1","需要插入的字段n":"值n"},"d":是否删除目标，默认不删除，会自动将图片设为空} 在uploadExtra中可以设置
     * @return array
     */
    public function run()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //header('Access-Control-Allow-Origin:*');//注意！跨域要加这个头 上面那个没有
        if (!Yii::$app->request->isPost){
            return ['error'=>'无效的上传方式！'];
        }

        $model=new JqUpload(['config'=>$this->config]);
        $model->model=$this->model;
        $model->file=UploadedFile::getInstances($model,'file');//接收文件

        //print_r(Yii::$app->request->post());
        //默认接受的字段是file_id 为上传区域第几个文件，从0开始；然后能直接接受到uploadExtraData里面的数据

        //如果有插入数据请求
        if ($date=Yii::$app->request->post('data')){
            $date=json_decode($_POST['data'],true);
            $class_name=$date['cn'];

            //插入的数据里的图片字段名
            if (!isset($date['c'])){
                $picName='pic_id';
            }else{
                $picName=$date['c'];
            }
        }else{
            $class_name=null;
        }

        if (Yii::$app->request->isAjax){
            //异步上传的
          //  $bt=Yii::$app->db->beginTransaction();
           // try {
                if ($model->upload($this->options, $picName)) {
                    $result = ['append' => true];
                    if ($date and $class_name) {
                        foreach ($model->upfile['file'] as $k => $up) {
                            if ($up) {
                                //插入一条数据   类名       请求json   图片字段   图片id
                                if (($e = $model->into($class_name, $date['iq'], $picName, $up['id'])) === true) {
                                    //数组，图像文件列表或任何HTML标记来指向您上传的文件。插件将自动在上传成功后在预览内容中动态替换文件。
                                    $result['initialPreview'][$k] = Yii::$app->params['web_cdn'] . $up['url'];
                                    //标识initialPreview项目中每个文件标记的属性的配置
                                    $result['initialPreviewConfig'][$k] = [
                                        'caption' => $up['name'],
                                        'url' => Url::to(['file-del']),
                                        'key' => $up['id'],//如果这里放文件ID，下面的id就可以不要了
                                        'extra' => [
                                            'fid' => $up['id'],//图片或文件ID
                                            Yii::$app->request->csrfParam => Yii::$app->request->post(Yii::$app->request->csrfParam),
                                            //Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
                                        ],
                                    ];
                                } else {//插入失败
                                    JqUpload::del($up['id']);//删除文件
                                  //  $bt->rollBack();//回滚

                                    if (isset($e['file'])) {
                                        return [
                                            'error' => $e['file'][0] . '，数据插入失败，图片上传无效！请重试！',
                                        ];
                                    } else {
                                        return [
                                            'error' => '图片上传失败！请重试！',
                                        ];
                                    }

                                }
                            }
                        }
                    } else {
                        foreach ($model->upfile['file'] as $k => $up) {
                            if ($up) {
                                //数组，图像文件列表或任何HTML标记来指向您上传的文件。插件将自动在上传成功后在预览内容中动态替换文件。
                                $result['initialPreview'][$k] = Yii::$app->params['web_cdn'] . $up['url'];
                                //标识initialPreview项目中每个文件标记的属性的配置
                                $result['initialPreviewConfig'][$k] = [
                                    'caption' => $up['name'],
                                    'url' => Url::to(['file-del']),
                                    'key' => $up['id'],//如果这里放文件ID，下面的id就可以不要了
                                    'extra' => [
                                        'fid' => $up['id'],//图片或文件ID
                                        Yii::$app->request->csrfParam => Yii::$app->request->post(Yii::$app->request->csrfParam),
                                        //Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
                                    ],
                                ];
                            }
                        }
                    }

                    //完全正确
                  //  $bt->commit();
                    return $result;
                }else{
                    //上传失败
                  //  $bt->rollBack();
                }
         //   }catch (\Exception $e){
                //发生异常
               // $bt->rollBack();
                return [
                    'error' => '图片上传处理发生异常，上传失败！请重试！',
                ];
          //  }

            if ($model->hasErrors('file')){
                return [
                    'error'=>$model->errors['file'][0],
                ];
            }else{
                return [
                    'error'=>'文件上传失败！',
                ];
            }
        } else {
            //同步上传的
            $bt=Yii::$app->db->beginTransaction();
            try {
                if ($model->upload($this->options, $picName)) {
                    $result = ['append' => true];
                    if ($date) {
                        foreach ($model->upfile['file'] as $k => $up) {
                            if ($up) {
                                //插入一条数据   类名       请求json   图片字段   图片id
                                if (($e = $model->into($class_name, $date->iq, $picName, $up['id'])) === true) {

                                    //数组，图像文件列表或任何HTML标记来指向您上传的文件。插件将自动在上传成功后在预览内容中动态替换文件。
                                    $result['initialPreview'][$k] = Yii::$app->params['web_cdn'] . $up['url'];
                                    //标识initialPreview项目中每个文件标记的属性的配置
                                    $result['initialPreviewConfig'][$k] = [
                                        'caption' => $up['name'],
                                        'url' => Url::to(['file-del']),
                                        'key' => $up['id'],//如果这里放文件ID，下面的id就可以不要了
                                        'extra' => [
                                            'fid' => $up['id'],
                                            Yii::$app->request->csrfParam => Yii::$app->request->post(Yii::$app->request->csrfParam),
                                        ],
                                    ];
                                } else {//插入失败
                                    JqUpload::del($up['id']);//删除文件
                                    $bt->rollBack();//回滚
                                    if (isset($e['file'])) {
                                        return [
                                            'error' => $e['file'][0] . '，数据插入失败，图片上传无效！请重试！',
                                        ];
                                    } else {
                                        return [
                                            'error' => '图片上传失败！请重试！',
                                        ];
                                    }

                                }
                            }
                        }
                    } else {
                        $result = ['append' => true];
                        foreach ($model->upfile['file'] as $k => $up) {
                            if ($up) {
                                //数组，图像文件列表或任何HTML标记来指向您上传的文件。插件将自动在上传成功后在预览内容中动态替换文件。
                                $result['initialPreview'][$k] = Yii::$app->params['web_cdn'] . $up['url'];
                                //标识initialPreview项目中每个文件标记的属性的配置
                                $result['initialPreviewConfig'][$k] = [
                                    'caption' => $up['name'],
                                    'url' => Url::to(['file-del']),
                                    'key' => $up['id'],//如果这里放文件ID，下面的id就可以不要了
                                    'extra' => [
                                        'fid' => $up['id'],
                                        Yii::$app->request->csrfParam => Yii::$app->request->post(Yii::$app->request->csrfParam),
                                    ],
                                ];
                            }
                        }
                    }

                    $bt->commit();
                    return $result;
                }else{
                    $bt->rollBack();//回滚
                }
            }catch (\Exception $e){
                $bt->rollBack();//回滚
                return [
                    'error' => '图片上传处理发生异常，上传失败！请重试！',
                ];
            }

            if ($model->hasErrors('file')){
                return [
                    'error'=>$model->errors['file'][0],
                    //同步上传，多个文件时，指定哪些文件出错了（接收到的文件数据的基于零开始的索引）
                    'errorkeys'=>array_column(Yii::$app->request->post(),'file_id'),
                ];
            }else{
                return [
                    'error'=>'文件上传失败！',
                    //同步上传，多个文件时，指定哪些文件出错了（接收到的文件数据的基于零开始的索引）
                    'errorkeys'=>array_column(Yii::$app->request->post(),'file_id'),
                ];
            }

        }
    }
}