<?php
/**
 * JqUpload 支持异步和同步上传的业务模型，同时支持删除

 */

namespace common\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

class JqUpload extends Model {
    public $file;//接收文件的变量
    public $pic_id;//上传完成后的文件ID
    public $upfile;//上传完成后的信息数组
    public $config=[];//上传规则配置数组
    private $default_config=['skipOnEmpty'=>false,'maxFiles' => 10,'cut'=>false];//默认配置
    public $model='pic';//pic 使用 UploadImg,pic2 使用 UploadImg2, 其他使用 file

    public function attributeLabels()
    {
        return [
            'file' => '文件',
        ];
    }

    public function rules()
    {
        $this->config=array_merge($this->default_config,$this->config);//加入配置

        if ($this->model=='pic') {
            return [
                //第二步，规则验证，通过 file 验证器，你可以检查文件的扩展名，大小，MIME类型等等
                // 如果你要上传的是一张图片，可以考虑使用image验证器，确保对应的模型属性 收到的文件是有效的图片文件，然后才保存，或者使用扩展类Imagine Extension进行处理.
                //如果上传多个文件，'maxFiles' => 4 可最多允许上传4个  不允许中断
                UploadImg::rules('file', $this->config),
            ];
        }elseif ($this->model=='pic2'){
            return [
                UploadImg2::rules('file', $this->config),
            ];
        }else{
            return [
                Upload::rules('file', $this->config),
            ];
        }
    }

    /**
     * 配置上传组件和初始化预览图片
     * @param null|array $pic_ids 初始图片ID数组
     * @param array $data 另外需要插入数据库的数据
     * @param array $config 上传插件的配置
     * @param null|string $model 上传文件处理模型简写
     * @return array
     */
    public static function initOptions($pic_ids=null,$data=null,$config=[],$model=null){
        //默认配置
        $default_config=[
            'upUrl'=>'file-up',//默认上传路径
            'minFileSize'=>5,
            'maxFileSize'=>2014,
            'minFileCount'=>1,
            'maxFileCount'=>10,
            'required'=>true,
            'uploadExtraData'=>[],
            'deleteExtraData'=>[],
            'allowedFileTypes'=>['image'],
            'allowedFileExtensions'=>[ 'jpg' ,'gif' ,'png'],
        ];
        $config=array_merge($default_config,$config);

        //初始化已有图片
        $initImg=self::initImg($pic_ids,$model);

        //返回配置数组
        return [
            'initialPreview' => isset($initImg['initialPreview'])?$initImg['initialPreview']:[],// 预览的文件
            'initialPreviewConfig' => isset($initImg['initialPreviewConfig'])?$initImg['initialPreviewConfig']:[],//使用数据键设置初始预览
            'initialPreviewAsData' => true,
            //'uploadUrl' => Url::to(['/file-up/async']),// 要使用AJAX上传，必须设置uploadUrl属性。异步上传的接口地址设置
            'uploadUrl' => Url::to([$config['upUrl']]),// 要使用AJAX上传，必须设置uploadUrl属性。异步上传的接口地址设置
            'uploadExtraData' => array_merge(['data'=>json_encode($data),Yii::$app->request->csrfParam => Yii::$app->request->getCsrfToken()],$config['uploadExtraData']),
            'minFileSize'=>$config['minFileSize'],//浮点数，以KB为单位的最小文件大小。文件大小必须超过此处设置的值，否则使用msgSizeTooSmall设置抛出验证错误。默认为0。如果这被设置为null，则跳过验证，并且不执行最小值检查。
            'maxFileSize'=>$config['maxFileSize'],//float，以KB上载的最大文件大小。如果大于此，将使用msgSizeTooLarge设置抛出验证错误。如果设置为0，则表示允许的大小是无限制的。默认为0。
            'minFileCount'=>$config['minFileCount'],//int每次多次上传允许的最少文件数。如果设置为0，则表示文件数是可选的。默认为0。
            'maxFileCount'=>$config['maxFileCount'],//int每次上传允许的最大文件数。如果设置为0，则表示允许的文件数量是无限制的。默认为0。
            'validateInitialCount'=>true,//是否包括在初始确认预览文件数（服务器上传文件）minFileCount和maxFileCount。默认为false。
            'autoReplace'=>false,//是否在maxFileCount达到限制后自动替换预览中的文件，并选择一组新的文件
            'overwriteInitial'=>false,//覆盖初始显示的文件
            'uploadAsync' => true,//开启异步上传，默认模式
            'browseOnZoneClick' => true,
            'required'=>$config['required'],//上传之前是否强制文件选择
            'deleteExtraData'=>array_merge([
                Yii::$app->request->csrfParam => Yii::$app->request->getCsrfToken(),
            ],$config['deleteExtraData']),
            //'deleteUrl'=>Url::to(['/file-up/del']),
            'deleteUrl'=>Url::to(['file-del']),
            'allowedFileTypes'=>$config['allowedFileTypes'],
            'allowedFileExtensions'=>$config['allowedFileExtensions'],
        ];
    }

    /**
     * 一些事件行为
     * @return array
     */
    public static function initEvents(){
        return [
            //删除之前，在此拦截ajax删除
            'filebeforedelete'=>'function(event, key, data) {
                    var aborted = !window.confirm(\'确定删除这个文件?\');
                    if (aborted) {
                        window.alert(\'文件已被您中止删除! \');
                    };
                    return aborted;
                    console.log(\'Key = \' + key);
                }',
        ];
    }

    /**
     * 初始化已有图片
     * @param null $pic_ids 初始化图片ID数组，请先查询出来
     * @param array $model 上传的处理模型
     * @param $date {"t":"user-info","i":10003}
     * @return array ['model'=>$model 上传,'result'=>$result]
     */
    public static function initImg($pic_ids=null,$model=null){
        $csrf_key=Yii::$app->request->csrfParam;
        $csrf_value=Yii::$app->request->csrfToken;

        if (is_array($pic_ids)) {
            if ($model=='pic' or empty($model)){
                $imgs = Pic::findAll($pic_ids);
            }elseif ($model=='pic2'){
                $imgs = Pic2::findAll($pic_ids);
            }else{
                $imgs = File::findAll($pic_ids);
            }

            $result = ['append' => true];

            foreach ($imgs as $k => $img) {
                //数组，图像文件列表或任何HTML标记来指向您上传的文件。插件将自动在上传成功后在预览内容中动态替换文件。
                $result['initialPreview'][$k] = Yii::$app->params['web_cdn'] . $img->path;
                //标识initialPreview项目中每个文件标记的属性的配置

                //没有表名，则不删除目标
                $result['initialPreviewConfig'][$k] = [
                    'url' => Url::to(['file-del']),
                    'key' => $img->id,//如果这里放文件ID，下面的id就可以不要了
                    'extra' => [
                        'fid' => $img->id,//图片或文件ID
                        $csrf_key => $csrf_value//csrf
                    ],
                ];
            }
        }else{
            $result=[];
        }

        return $result;
    }

    /**
     * 上传，文件验证须在调用此方法前配置$this->config
     * @param string $model 上传的处理模型
     * @param array $options 图片处理数组 $options=['th_width'=>600,'th_height'=>400,'item'=>File::ITEM_USER_IMG] 可以加上 item
     * @param string $picName 接收图片、文件ID 的属性
     * @return bool
     */
    public function upload($options=['th_width'=>400,'th_height'=>400], $picName){
        if (!self::validate()){
            return false;
        }

        if ($this->model=='pic'){
            $up=new UploadImg();//上传和处理并可获得ID等更多信息，自动赋值给模型需要的属性，和报错
        }elseif ($this->model=='pic2'){
            $up=new UploadImg2();//上传和处理并可获得ID等更多信息，自动赋值给模型需要的属性，和报错
        }else{
            $up=new Upload();//上传和处理并可获得ID等更多信息，自动赋值给模型需要的属性，和报错
        }

        if ($up->up($this,'file', $picName,$options)===false){
            $this->addError('file','图片上传处理失败');
            return false;
        }
        $this->upfile=$up->upfile;

        return true;
    }


    /**
     * 删除一个文件
     * @param $id 文件ID
     * @param $model 上传处理模型
     * @return bool
     */
    public static function del($id,$model='pic')
    {
        if ($model=='pic'){
            if ($pic=Pic::findOne(['id'=>$id,'user_id'=>Yii::$app->user->id])){
                return $pic->delete();
            }
        }elseif ($model=='pic2'){
            if ($pic=Pic2::findOne(['id'=>$id,'user_id'=>Yii::$app->user->id])){
                return $pic->delete();
            }
        }else{
            if ($file=File::findOne(['id'=>$id,'user_id'=>Yii::$app->user->id])){
                return $file->delete();
            }
        }

        return false;
    }

    /**
     * 插入数据
     * @param string $class 类名，这里只使用公共类名，且使用SCENARIO_JQ_UPLOAD插入
     * @param array $date 目标数据数组
     * @param string $picName 文件接收属性名
     * @param integer $pic_id 文件或图片ID
     * @return bool
     */
    public function into($class,$date,$picName,$pic_id){
        if (class_exists('common\models\\'.$class)) {
            $class='common\models\\'.$class;
            $model = new $class();
            $model->scenario=$model::SCENARIO_JQ_UPLOAD;

            foreach ($date as $k => $v){
                $model->$k=$v;
            }
//如果数据存在
            $a= $model::findOne($model->id);
            if($a){
                $a->$picName=$pic_id;
                if($a->save(false)){
                    return true;
                }else{
                    return false;
                }
            }else{
                //如果数据不存在
                $model->$picName=$pic_id;
                if ($model->save(false)){
                    return true;
                }else{
                    return $model->errors;
                }

            }


        }

        return false;
    }
}