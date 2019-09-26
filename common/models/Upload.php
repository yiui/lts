<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/5/20 Time: 16:40
 */

namespace common\models;

use common\helpers\Str;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use Imagine\Image\ImageInterface;

/**
 * 实例：
 * $up=JqUpload(['skipOnEmpty'=>'bool 默认假不跳过','maxSize'=>200000]);
 * //一般配置
 * $info=$up->ipload(['item'=>FIle::ITEM_XXX,'user_id'=>'用户id，默认空：当前登录的','thumb'=>'bool 是否缩放，默认缩放','throw'=>'bool 是否抛出异常，默认不抛']);
 * if($info===null){
 *      echo '没有上传文件';
 * }
 * if($info===false){
 *      echo '上传或处理失败';
 * }
 * if($info){
 *      echo '上传和处理成功';
 * }
 * Class Upload
 * @package common\models
 */
class Upload {
    public $file;//保存上传的文件
    public $upfile;//上传和处理后的文件信息数组
    public $config;//上传配置

    /**
     * 接收文件到模型的某个属性
     * @param $model
     * @param $att
     * @param bool $many 是否多文件上传
     * @return UploadedFile|UploadedFile[]
     */
    public static function start($model,$att,$many=false){
        if ($many==false){
            return UploadedFile::getInstance($model, $att);
        }else {
            return UploadedFile::getInstances($model, $att);
        }
    }

    /**
     * 给使用者使用的验证字段
     * 在使用处，直接静态获取
     * @param string $name 字段名
     * @param array $this->config 验证的配置数组
     * @return array
     */
    public static function rules($name,$config=[])
    {
        //普通文件上传
        if (empty($config['image'])) {
            //默认配置
            $default_config = array(
                'skipOnEmpty' => false,//是否跳过不上传
                'name'=>'pic',
                'maxSize' => 3000000,//最大限制
                'maxFiles' => 1,//最多允许上传4个
//                'mimeTypes' => 'image/jpeg,image/jpg,image/png,image/gif',//文件mime类型限制
//                'extensions' => 'png,jpg,gif,jpe,jpeg',//扩展名限制
                'on' => null,
            );

            $config = array_merge($default_config, $config);

            return [
                //第二步，规则验证，通过 file 验证器，你可以检查文件的扩展名，大小，MIME类型等等
                // 如果你要上传的是一张图片，可以考虑使用image验证器，确保对应的模型属性 收到的文件是有效的图片文件，然后才保存，或者使用扩展类Imagine Extension进行处理.
                //如果上传多个文件，'maxFiles' => 4 可最多允许上传4个
                $name,
                'file',
                'maxFiles' => $config['maxFiles'],
                'skipOnEmpty' => $config['skipOnEmpty'],
                'message' => '文件上传失败',
                'maxSize' => $config['maxSize'],//对每个子文件进行的判断
                'tooBig' => '上传的文件过大，不应超过 ' . $config['maxSize'] . ' kb',
                'mimeTypes' => $config['mimeTypes'],
                'wrongMimeType' => '上传的文件类型不允许',
                'extensions' => $config['extensions'],
                'wrongExtension' => '上传的文件类型不允许',
                'on' => $config['on'],
                'checkExtensionByMimeType'=>true,//是否通过文件的 MIME 类型来判断其文件扩展
            ];
        }else{
            //图片上传，具有像素宽度约束
            //默认配置
            $default_config = array(
                'skipOnEmpty' => false,//是否跳过不上传
                'maxSize' => 2000000,//最大限制
                'maxFiles' => 1,//最多允许上传4个
                'mimeTypes' => 'image/jpeg,image/jpg,image/png,image/gif',//文件mime类型限制
                'extensions' => 'png,jpg,gif,jpe,jpeg',//扩展名限制
                'on' => null,
                'minWidth' => 50,
                'maxWidth' => 5000,
                'minHeight' => 50,
                'maxHeight' => 5000,
            );

            $config = array_merge($default_config, $config);

            return [
                //第二步，规则验证，通过 file 验证器，你可以检查文件的扩展名，大小，MIME类型等等
                // 如果你要上传的是一张图片，可以考虑使用image验证器，确保对应的模型属性 收到的文件是有效的图片文件，然后才保存，或者使用扩展类Imagine Extension进行处理.
                //如果上传多个文件，'maxFiles' => 4 可最多允许上传4个
                $name,
                'image',
                'maxFiles' => $config['maxFiles'],
                'skipOnEmpty' => $config['skipOnEmpty'],
                'message' => '文件上传失败',
                'maxSize' => $config['maxSize'],//对每个子文件进行的判断
                'tooBig' => '上传的文件过大，不应超过 ' . $config['maxSize'] . ' kb',
                'mimeTypes' => $config['mimeTypes'],
                'wrongMimeType' => '上传的文件类型不允许',
                'extensions' => $config['extensions'],
                'wrongExtension' => '上传的文件类型不允许',
                'on' => $config['on'],
                'minWidth' => $config['minWidth'],
                'maxWidth' => $config['maxWidth'],
                'minHeight' => $config['minHeight'],
                'maxHeight' => $config['maxHeight'],
                'checkExtensionByMimeType'=>true,//是否通过文件的 MIME 类型来判断其文件扩展
            ];
        }
    }

    /**
     * 上传配置
     *
     * $this->config=array_merge($this->config,self::upConfig($this->config));
     *
     * @param array $this->config
     * @return array
     */
    public function upConfig($config){
        //默认配置
        $default_config=array(
            //不同类型保存路径不同
            'item'=>File::ITEM_DEFAULT,//可以使用名称也可以使用类型ID号
            'status'=>File::STATUS_DEFAULT,//可以使用名称也可以使用状态ID号

            'path'=>'@static/uploads/file',//默认保存路径
            //'url'=>'',//相对路径或前缀,这是程序自己获取

            'filename'=>null,//取的名字，空取随机字符串名，否则在名字后加上时间和随机数，保证文件名唯一性

            //众多文件其中某个出现错误是否中断
            //中断则直接返回异常或假(不管已经上传文件多少，全部删除)，退出上传
            //否则某个处理的文件数组返回假（不再抛出异常，删除此文件），继续下个上传
            'cut'=>true,//只有中断，然后'throw'=>true 才 抛出异常

            'throw'=>false,//保存或上传失败，是否抛出异常（为了给事务处理使用的），否则返回假

            //date 采用年月日保存，user 采用用户ID，或直接采用此字符串的文件夹保存
            'sondir'=>'date',//下放到子目录，实际目录则为 path.sondir

            'thumb'=>true,//是否开启缩放（开启后缩放不成功会报错）
            'th_name'=>false,//缩放后文件名 或 =true在源名称后加上长宽大小，=false直接使用原名，会直接覆盖源文件哦
            'th_width'=>400,//缩略后的最大宽度
            'th_height'=>400,//缩略后的最大高度
            'th_mode'=>2,//压缩后 1 裁剪，2 填充

            'crop'=>false,//是否开启裁剪（开启后后，最好关闭缩放，不成功会报错）
            'cp_name'=>false,//缩放后文件名 或 =true在源名称后加上长宽大小，=false直接使用原名，会直接覆盖源文件哦
            'cp_position'=>5,//裁剪的位置，如果有此参数则cp_wh不起作用，并以此计算出具体开始裁剪的位置
            //left-top 1 左上，center-top 2 中上,right-top 3 右上角，
            //left-center 4 左中部，center-center 5 正中,right-center 6 右中
            // left-bottom 7 左下角，center-bottom 8 中下部，right-bottom 9 右下角
            'cp_wh'=>[0,0],//裁剪开始的位置
            'cp_width'=>400,//裁剪的最大宽度
            'cp_height'=>400,//裁剪后的最大高度

            'rotate'=>false,//是否旋转
            'rt_name'=>false,
            'rt_margin'=>0,//旋转后边框像素大小
            'rt_color'=>'fff',//边框背景颜色
            'rt_alpha'=>0,//边框背景颜色
            'rt_angle'=>90,//旋转角度

            'text'=>false,//是否添加文字水印
            'tt_name'=>false,
            'tt_config'=>['color'=>'f00','size'=>40,'angle'=>5],//文字设置
            'tt_font'=>'@static/common/font/COOPBL.TTF',//使用字体
            'tt_content'=>Yii::$app->name,//水印文字内容
            'tt_pos'=>[10,10],//水印位置，左上角

            'mark'=>false,//是否添加图片水印
            'mk_name'=>false,
            'mk_img'=>'@static/common/img/watermark.png',//水印图片
            'mk_pos'=>[10,10],//水印位置

            'quality'=>100,//保存图片后的质量：save('11_rotate.jpg', ['quality' => $this->config['quality']]);

            'getsize'=>false,//是否获取图片大小？
            'getmore'=>false,//是否获取更新信息？

            'delete'=>true,//是否删除原图

            'savedb'=>true,//是否保存到数据库（开启后保存不成功会报错）
            'user_id'=>null,//如果保存数据库时，不提供user_id，则使用当前登录的用户id
        );
        //用户头像的默认设置
        if ($this->config['item']=='用户头像'){
            $this->config['thumb']=true;
            $this->config['th_name']=false;
            $this->config['sondir']=false;
            $this->config['th_width']=120;
            $this->config['th_height']=120;
            $this->config['status']=File::STATUS_ACTIVE;
        }
        $this->config=array_merge($default_config,$config);
    }

    /**
     * 处理获得path保存路径和url（特殊类型检查权限）
     *
    if (!is_array($pathurl=self::pathUrl($this->config))){
    $this->addError('file',$pathurl);
    return false;
    }else{
    $path=$pathurl['path'];
    $url=$pathurl['url'];
    }
     *
     * @param $this->config 配置文件
     * @return array path和url索引数组
     * @throws ForbiddenHttpException
     */
    public function pathUrl(){
        if (is_numeric($this->config['item'])){
            $this->config['item']=Items::i2V($this->config['item']);
        }
        //分类处理和保存（特殊类型应该要检查权限）
        switch ($this->config['item']){
            case File::ITEM_USER_TX:
                $this->config['path']='@static/tx';
                break;
            case File::ITEM_USER_IMG:
                $this->config['path']='@static/uploads/img';
                $this->config['mark']=true;
                break;
            case File::ITEM_USER_FILE:
                $this->config['path']='@static/uploads/file';
                $this->config['mark']=true;
                break;
            case File::ITEM_BACKUPS_FILE:
                // if (!Yii::$app->user->can('file_add')){
                //     throw new ForbiddenHttpException('您没有上传此类文件的权限！');
                // }
                $this->config['path']='@static/backups/file';
                break;
            case File::ITEM_BACKUPS_IMG:
                // if (!Yii::$app->user->can('file_add')){
                //     throw new ForbiddenHttpException('您没有上传此类文件的权限！');
                // }
                $this->config['path']='@static/backups/img';
                break;
            case File::ITEM_COMMOM_FILE:
                // if (!Yii::$app->user->can('file_add')){
                //     throw new ForbiddenHttpException('您没有上传此类文件的权限！');
                // }
                $this->config['path']='@static/common/file';
                break;
            case File::ITEM_COMMOM_IMG:
                // if (!Yii::$app->user->can('file_add')){
                //     throw new ForbiddenHttpException('您没有上传此类文件的权限！');
                // }
                //$this->config['mark']=true;看你需要了，一般不要水印
                $this->config['path']='@static/common/img';
                break;
            default:
                $this->config['path']='@static/uploads/file';
                $this->config['mark']=true;
                break;
        }

        //获取相对根目录的路径
//        $this->config['url'] = rtrim(ltrim($this->config['path'], '@'),'/') . '/';//注意，处理添加进配置中的url前缀
        $this->config['url'] = rtrim(Str::ltrim($this->config['path'],'@static/'),'/').'/';//注意，处理添加进配置中的url前缀

        //获取绝对路径，没有创建
        $this->config['path'] = str_replace('/', DIRECTORY_SEPARATOR, Yii::getAlias(rtrim($this->config['path'], '/'))). DIRECTORY_SEPARATOR;
        //是否需要创建子目录
        if ($this->config['sondir']!=false) {
            if ($this->config['sondir'] == 'date') {
                $this->config['path'] .= date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . date('d') . DIRECTORY_SEPARATOR;
                $this->config['url'] .= date('Y') . '/' . date('m') . '/' . date('d') . '/';
            } else if ($this->config['sondir'] == 'user') {
                $this->config['path'] .= Yii::$app->user->id ? Yii::$app->user->id : date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . date('d') . DIRECTORY_SEPARATOR;
                $this->config['url'] .= Yii::$app->user->id ? Yii::$app->user->id : date('Y') . '/' . date('m') . '/' . date('d') . '/';
            } else if (is_string($this->config['sondir'])) {
                $this->config['path'] .= $this->config['sondir']. DIRECTORY_SEPARATOR;
                $this->config['url'] .= $this->config['sondir'].'/';
            }
        }
        //创建目录
        if (!is_dir($this->config['path'])) {
            FileHelper::createDirectory($this->config['path']);
        }
    }

    /**
     * 获取新的名字
     * @param null $filename 名字
     * @param null $item    类型，特殊类型，名字不一样，如头像，采用人的ID
     * @return string 新的名字
     */
    public function getNewName(){
        //处理新的文件名
        if (empty($this->config['filename'])) {
            $newname = Yii::$app->getSecurity()->generateRandomString();
        }else{
            $newname=$this->config['filename'].sprintf('%u',ip2long(Yii::$app->request->userIP)).date('YmdHis').rand(40000,88888);
        }
        if (!empty($this->config['item']) and $this->config['item']=='用户头像'){
            $newname='tx_'.Yii::$app->user->id;
        }
        return $newname;
    }

    /**
     * 上传和处理
     *  $up=new Upload();//上传和处理并可获得ID等更多信息，自动赋值给模型需要的属性，和报错
        $up->up($this,'front_pic','front_id');
        $up->up($this,'back_pic','back_id');
     * @param Model $obj 接收文件的业务模型
     * @param string $fromname 表单名或叫接受文件的模型属性
     * @param string $toname    接受文件ID的模型属性，多个文件可以使用数组哦:$toname='pic_id[]'
     * @param array $config 上传配置数组
     * @return bool 假代表上传或处理失败，null则代表没有上传文件，数组代表上传和处理成功
     * @throws BadRequestHttpException
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function up(Model &$model,$att='file',$toatt='pic_id',array $config=[]){
        //上传了才处理
        if ($model->$att) {
            //分类处理和保存（特殊类型应该要检查权限）
            self::upConfig($config);//配置初始化
            self::pathUrl();//获得path和url

            //如果是单文件上传，归入 0 索引
            if (!empty($model->$att->name)){
                $allfile[]=$model->$att;
            }else{
                $allfile=$model->$att;
            }

            //处理每个文件
            foreach ($allfile as $k => $file) {
                //处理新的文件名
                $this->upfile[$att][$k]['name']=self::getNewName();

                //保存文件：
                if ($file->saveAs($this->config['path']. $this->upfile[$att][$k]['name'] . '.' . $file->extension)){
                    $this->upfile[$att][$k]['path']=$this->config['path'] . $this->upfile[$att][$k]['name'] . '.' . $file->extension;
                    $this->upfile[$att][$k]['url']=$this->config['url'] . $this->upfile[$att][$k]['name'] . '.' . $file->extension;
//                    $this->upfile[$att][$k]['size']=$file->size;//文件大小

                    try{
                        //处理缩放,必须是图片文件
                        if (strpos($file->type,'image')!==false) {
                            //获取图片长宽
                            list($this->upfile[$att][$k]['width'],$this->upfile[$att][$k]['height'])=getimagesize($this->upfile[$att][$k]['path']);

                            //处理图片
                            if ($this->doimg($file, $att, $k)===false){
                                $model->addError($att,'上传的图片处理失败。');
                                return false;
                            }

                            //是否获取图片长宽
                            if ($this->config['getsize']) {
                                //图片长宽
                                $size = getimagesize($this->upfile[$att][$k]['path']);
                                $this->upfile[$att][$k]['width'] = $size[0];
                                $this->upfile[$att][$k]['height'] = $size[1];
                            }
                        }
                        //保存到数据库
                        if ($this->config['savedb']){
                            //成功赋值ID，失败则不赋值
                            if ($this->savedb($file, $att, $k)===false){
                                $model->addError($att,'上传的文件保存到数据库失败。');
                                return false;
                            }else{
                                if ($this->getIds($att)){
                                    $model->$toatt=$this->getIds($att);
                                }
                            }
                            //---------------------------------------------
                            //注意 如果需要，在模型保存之后，写上删除更新之前的文件
                            //注意 如果需要，在模型删除之后，写上删除之前的文件
                        }
                    }catch (\Exception $e){
                        //出现异常
                        $this->delete();//删除上传的所有文件
                        throw $e;//继续抛出此异常给其他地方使用
                    }

                    //是否获取更多信息
                    if ($this->config['getmore']) {
                        //更多信息
                        $this->upfile[$att][$k]['local'] = $file->name;//上传前的名字
                        $this->upfile[$att][$k]['size'] = $file->size;//实际大小
                        $this->upfile[$att][$k]['type'] = $file->type;//mime类型
                        $this->upfile[$att][$k]['temp'] = $file->tempName;//临时文件
                        //$this->upfile[$att][$k]['name'] 名字
                        //以下成功的话，已经获得了
                        //还有$this->upfile[$att][$k]['path'] 绝对路径
                        //还有$this->upfile[$att][$k]['url'] 相对路径
                        //还有$this->upfile[$att][$k]['id'] 保存在file表中的id，一般只需要id
                    }

                }else{
                    //保存失败
                    if ($this->config['cut']) {
                        //删除上传后、缩放前的文件
                        $this->delete();
                        if ($this->config['throw']) {
                            throw new Exception('保存文件失败！');
                        }
                    }else{
                        $this->upfile[$att][$k]=false;
                    }

                    $model->addError($att,'上传的文件保存失败。');
                    return false;
                }
            }
            return true;
        }
    }

    /**
     * 获取裁剪的准确定位点
     * @param $att 接受文件的表单名
     * @param $k 第几个文件，和上方$att 组合可以找到当前图片的信息
     * @param int $w 裁剪后的图片长度
     * @param int $h 裁剪后的宽度
     * @return array 裁剪的定位点数组参数
     */
    private function getPosition($att,$k,$w=400,$h=400){
        $width=$this->upfile[$att][$k]['width'];
        $height=$this->upfile[$att][$k]['height'];

        //left-top 1 左上，center-top 2 中上,right-top 3 右上角，
        //left-center 4 左中部，center-center 5 正中,right-center 6 右中
        // left-bottom 7 左下角，center-bottom 8 中下部，right-bottom 9 右下角
        if ($this->config['cp_position']){
            switch ($this->config['cp_position']){
                case 'left-top':
                case 1:
                    $position=[0,0];
                    break;
                case 'center-top':
                case 2:
                    if ($width>$w) {
                        $x=floor(($width-$w)/2);
                    }else{
                        $x=0;
                    }
                    $position=[$x,0];
                    break;
                case 'right-top':
                case 3:
                    if ($width > $w){
                        $x=$width-$w;
                    }else{
                        $x=0;
                    }
                    $position=[$x,0];
                    break;
                case 'left-center':
                case 4:
                    if ($height>$h){
                        $y = floor(($height-$h)/2);
                    }else{
                        $y=0;
                    }

                    $position=[0,$y];
                    break;
                case 'center-center':
                case 5:
                    if ($width>$w) {
                        $x = floor(($width-$w)/2);
                    }else{
                        $x = 0;
                    }

                    if ($height>$h){
                        $y = floor(($height-$h)/2);
                    }else{
                        $y = 0;
                    }

                    $position=[$x,$y];
                    break;
                case 'right-center':
                case 6:
                    if ($width > $w){
                        $x=$width-$w;
                    }else{
                        $x=0;
                    }

                    if ($height>$h){
                        $y = floor(($height-$h)/2);
                    }else{
                        $y = 0;
                    }

                    $position=[$x,$y];
                    break;
                case 'left-bottom':
                case 7:
                    if ($height>$h){
                        $y = $height-$h;
                    }else{
                        $y = 0;
                    }

                    $position=[0,$y];
                    break;
                case 'center-bottom':
                case 8:
                    if ($width>$w) {
                        $x = floor(($width-$w)/2);
                    }else{
                        $x = 0;
                    }

                    if ($height>$h){
                        $y = $height-$h;
                    }else{
                        $y = 0;
                    }

                    $position=[$x,$y];
                    break;
                case 'right-bottom':
                case 9:
                    if ($width > $w){
                        $x=$width-$w;
                    }else{
                        $x=0;
                    }

                    if ($height>$h){
                        $y = $height-$h;
                    }else{
                        $y = 0;
                    }

                    $position=[$x,$y];
                    break;
                default:
                    $position=[0,0];
                    break;
            }
        }else{
            $position=$this->config['cp_wh'];
        }

        return $position;
    }

    public function doimg($file, $att, $k){
        $oldname=$this->upfile[$att][$k];
        //处理缩放,必须是图片文件
        if ($this->config['thumb'] and $this->upfile[$att][$k]) {
            //是否重新命名，有新名字或ture ，则不覆盖以前文件，不然会直接覆盖上传的源文件
            if ($this->config['th_name']===true){
                $this->upfile[$att][$k]['name']='th_'.$this->config['th_width'].'_'.$this->config['th_height'].'_'.$this->upfile[$att][$k]['name'];
            }else if (is_string($this->config['th_name'])){
                //虽然起了名字，但仍然防止同名
                $this->upfile[$att][$k]['name']='th_'.$this->config['th_width'].'_'.$this->config['th_height'].'_'.$this->config['th_name'].date('YmdHis').rand(40000,88888);
            }

            //缩放
            $img = Image::thumbnail($this->config['path']. $this->upfile[$att][$k]['name'] . '.' . $file->extension, $this->config['th_width'], $this->config['th_height'],$this->config['th_mode']==1?ImageInterface::THUMBNAIL_OUTBOUND:ImageInterface::THUMBNAIL_INSET);
            if ($img->save($this->config['path']. $this->upfile[$att][$k]['name'] . '.' . $file->extension, ['quality' => $this->config['quality']])) {
                //缩放成功
                if ($oldname['name']!=$this->upfile[$att][$k]['name'] and $this->config['delete']){
                    //删除上传后、缩放前的文件
                    $this->deleteold($oldname);//还没有保存到数据库
                }
                $oldname=$this->upfile[$att][$k];//现在的名字
                //添加缩放消息
                $this->upfile[$att][$k]['path']=$this->config['path'] . $this->upfile[$att][$k]['name']. '.'. $file->extension;
                $this->upfile[$att][$k]['url']=$this->config['url'] . $this->upfile[$att][$k]['name']. '.'. $file->extension;
            } else {
                //缩放失败，
                if ($this->config['cut']) {
                    //中断，删除所有上传的文件
                    $this->delete($att);

                    if ($this->config['throw']) {
                        throw new Exception('图片缩放失败！');
                    }else{
                        return false;
                    }
                }else{
                    //删除上传后、缩放前的文件
                    $this->delete($att,$k);//源文件
                }
            }
        }

        //裁剪
        if ($this->config['crop'] and $this->upfile[$att][$k]) {
            //是否重新命名，有新名字或ture ，则不覆盖以前文件，不然会直接覆盖上传的源文件
            if ($this->config['cp_name']===true){
                $this->upfile[$att][$k]['name']='cp_'.$this->config['cp_width'].'_'.$this->config['cp_height'] .'_'.$this->upfile[$att][$k]['name'];
            }else if (is_string($this->config['cp_name'])){
                //虽然起了名字，但仍然防止同名
                $this->upfile[$att][$k]['name']='cp_'.$this->config['cp_width'].'_'.$this->config['cp_height'] .'_'.$this->config['cp_name'].date('YmdHis').rand(40000,88888);
            }

            //裁剪
            $img = Image::crop($this->config['path']. $this->upfile[$att][$k]['name'] . '.' . $file->extension, $this->config['cp_width'], $this->config['cp_height'],$this->getPosition($att,$k,$this->config['cp_width'], $this->config['cp_height']));
            if ($img->save($this->config['path']. $this->upfile[$att][$k]['name'] . '.' . $file->extension, ['quality' => $this->config['quality']])) {
                //缩放成功
                if ($oldname['name']!=$this->upfile[$att][$k]['name'] and $this->config['delete']){
                    //删除上传后、缩放前的文件
                    $this->deleteold($oldname);//还没有保存到数据库
                }
                $oldname=$this->upfile[$att][$k];//现在的名字
                //添加消息
                $this->upfile[$att][$k]['path']=$this->config['path'] . $this->upfile[$att][$k]['name']. '.'. $file->extension;
                $this->upfile[$att][$k]['url']=$this->config['url'] . $this->upfile[$att][$k]['name']. '.'. $file->extension;
            } else {
                //失败，
                if ($this->config['cut']) {
                    //中断，删除所有上传的文件
                    $this->delete($att);

                    if ($this->config['throw']) {
                        throw new Exception('图片裁剪失败！');
                    }else{
                        return false;
                    }
                }else{
                    //删除上传后、缩放前的文件
                    $this->delete($att,$k);//源文件
                }
            }
        }

        //旋转
        if ($this->config['rotate'] and $this->upfile[$att][$k]){
            //是否重新命名，有新名字或ture ，则不覆盖以前文件，不然会直接覆盖上传的源文件
            if ($this->config['rt_name']===true){
                $this->upfile[$att][$k]['name']='rt_'.$this->upfile[$att][$k]['name'];
            }else if (is_string($this->config['rt_name'])){
                //虽然起了名字，但仍然防止同名
                $this->upfile[$att][$k]['name']='rt_'.$this->config['rt_name'].date('YmdHis').rand(40000,88888);
            }

            //旋转
            $img = Image::frame($this->config['path']. $this->upfile[$att][$k]['name'] . '.' . $file->extension, $this->config['rt_margin'], $this->config['rt_color'], $this->config['rt_alpha'])->rotate($this->config['rt_angle']);
            if ($img->save($this->config['path']. $this->upfile[$att][$k]['name'] . '.' . $file->extension, ['quality' => $this->config['quality']])) {
                //成功
                if ($oldname['name']!=$this->upfile[$att][$k]['name'] and $this->config['delete']){
                    //删除上传后、缩放前的文件
                    $this->deleteold($oldname);//还没有保存到数据库
                }
                $oldname=$this->upfile[$att][$k];//现在的名字
                //添加缩放消息
                $this->upfile[$att][$k]['path']=$this->config['path'] . $this->upfile[$att][$k]['name']. '.'. $file->extension;
                $this->upfile[$att][$k]['url']=$this->config['url'] . $this->upfile[$att][$k]['name']. '.'. $file->extension;
            } else {
                //失败，
                if ($this->config['cut']) {
                    //中断，删除所有上传的文件
                    $this->delete($att);

                    if ($this->config['throw']) {
                        throw new Exception('图片旋转失败！');
                    }else{
                        return false;
                    }
                }else{
                    //删除上传后、缩放前的文件
                    $this->delete($att,$k);//源文件
                }
            }
        }

        //文字水印
        if ($this->config['text'] and $this->upfile[$att][$k]){
            //是否重新命名，有新名字或ture ，则不覆盖以前文件，不然会直接覆盖上传的源文件
            if ($this->config['tt_name']===true){
                $this->upfile[$att][$k]['name']='tt_'.$this->upfile[$att][$k]['name'];
            }else if (is_string($this->config['tt_name'])){
                //虽然起了名字，但仍然防止同名
                $this->upfile[$att][$k]['name']='tt_'.$this->config['tt_name'].date('YmdHis').rand(40000,88888);
            }

            //文字水印
            $img = Image::text($this->config['path']. $this->upfile[$att][$k]['name'] . '.' . $file->extension, $this->config['tt_content'], $this->config['tt_font'], $this->config['tt_pos'],$this->config['tt_config']);
            if ($img->save($this->config['path']. $this->upfile[$att][$k]['name'] . '.' . $file->extension, ['quality' => $this->config['quality']])) {
                //成功
                if ($oldname['name']!=$this->upfile[$att][$k]['name'] and $this->config['delete']){
                    //删除上传后、缩放前的文件
                    $this->deleteold($oldname);//还没有保存到数据库
                }
                $oldname=$this->upfile[$att][$k];//现在的名字
                //添加缩放消息
                $this->upfile[$att][$k]['path']=$this->config['path'] . $this->upfile[$att][$k]['name']. '.'. $file->extension;
                $this->upfile[$att][$k]['url']=$this->config['url'] . $this->upfile[$att][$k]['name']. '.'. $file->extension;
            } else {
                //失败，
                if ($this->config['cut']) {
                    //中断，删除所有上传的文件
                    $this->delete($att);

                    if ($this->config['throw']) {
                        throw new Exception('加文字水印失败！');
                    }else{
                        return false;
                    }
                }else{
                    //删除上传后、缩放前的文件
                    $this->delete($att,$k);//源文件
                }
            }
        }

        //图片水印
        if ($this->config['mark'] and $this->upfile[$att][$k]){
            //是否重新命名，有新名字或ture ，则不覆盖以前文件，不然会直接覆盖上传的源文件
            if ($this->config['mk_name']===true){
                $this->upfile[$att][$k]['name']='mk_'.$this->upfile[$att][$k]['name'];
            }else if (is_string($this->config['mk_name'])){
                //虽然起了名字，但仍然防止同名
                $this->upfile[$att][$k]['name']='mk_'.$this->config['mk_name'].date('YmdHis').rand(40000,88888);
            }

            // 给一张图片加水印
            $img = Image::watermark($this->config['path']. $this->upfile[$att][$k]['name'] . '.' . $file->extension, Yii::getAlias($this->config['mk_img']), $this->config['mk_pos']);
            if ($img->save($this->config['path']. $this->upfile[$att][$k]['name'] . '.' . $file->extension, ['quality' => $this->config['quality']])) {
                //成功
                if ($oldname['name']!=$this->upfile[$att][$k]['name'] and $this->config['delete']){
                    //删除上传后、缩放前的文件
                    $this->deleteold($oldname);//还没有保存到数据库
                }
                //最后一个操作不需要此：
                //$oldname=$this->upfile[$att][$k];//现在的名字
                //添加缩放消息
                $this->upfile[$att][$k]['path']=$this->config['path'] . $this->upfile[$att][$k]['name']. '.'. $file->extension;
                $this->upfile[$att][$k]['url']=$this->config['url'] . $this->upfile[$att][$k]['name']. '.'. $file->extension;
            } else {
                //失败，
                if ($this->config['cut']) {
                    //中断，删除所有上传的文件
                    $this->delete($att);

                    if ($this->config['throw']) {
                        throw new Exception('加图片水印失败！');
                    }else{
                        return false;
                    }
                }else{
                    //删除上传后、缩放前的文件
                    $this->delete($att,$k);//源文件
                }
            }
        }
    }

    /**
     * 保存某个文件到数据库的文件表里
     * @param $file 上传的文件对象
     * @param $this->upfile[$att][$k]['name'] 文件名
     * @param $this->config 配置数组
     * @return bool 是否保存成功
     * @throws Exception 如果需要，上传失败时抛出异常
     */
    public function savedb($file,$att, $k){
        if ($this->upfile[$att][$k]){
            //保存到数据库
            $newfile = new File();
            //$newfile->scenario=File::SCENARIO_USER_CREATE;
            $newfile->path = $this->upfile[$att][$k]['url'];
            $newfile->size = $file->size;//实际大小
            if (is_numeric($this->config['status'])){
                $newfile->status_id=$this->config['status'];
            }else{
                $newfile->status_id=Status::v2I(File::getTableSchema()->name, $this->config['status']);
            }
            if (is_numeric($this->config['item'])){
                $newfile->item_id=$this->config['item'];
            }else{
                $newfile->item_id=Items::v2I(File::getTableSchema()->name, $this->config['item']);
            }

            //用户ID
            if (empty($this->config['user_id'])) {
                $newfile->user_id=Yii::$app->user->id;
            }else{
                $newfile->user_id=$this->config['user_id'];
            }

            if ($newfile->save()) {
                $this->upfile[$att][$k]['id']=$newfile->id;
                return true;
            } else {
                if ($this->config['cut']) {
                    //删除所有上传后的文件
                    $this->delete($att);

                    if ($this->config['throw']) {
                        throw new Exception('保存文件数据失败！');
                    }else{
                        return false;
                    }
                }else{
                    //删除上传后、缩放前的文件
                    $this->delete($att,$k);
                }
            }
        }
    }

    /**
     * 删除老文件
     * @param $file
     */
    public function deleteold(&$file){
        if ($file!==false){
            if ($this->config['savedb']){
                if ($f=File::findOne($file['id'])){
                    $f->delete();
                }else if (is_file($file['path'])){
                    unlink($file['path']);
                }
            }else{
                if (is_file($file['path'])){
                    unlink($file['path']);
                }
            }
            //卸载
            unset($file);
        }
    }

    /**
     * 删除刚上传的文件
     * @param $filename 或 下标
     * @param $this->config 配置数组，只需要：$this->config=['savedb'=>？]
     */
    public function delete($att=null, $k=null){
        if (empty($att) and empty($k)){
            //删除刚刚上传的全部文件
            foreach ($this->upfile as $att => $files){
                foreach ($files as $k => $file){
                    if ($file!==false){
                        if ($this->config['savedb']){
                            if ($f=File::findOne($file['id'])){
                                $f->delete();
                            }else if (is_file($file['path'])){
                                unlink($file['path']);
                            }
                        }else{
                            if (is_file($file['path'])){
                                unlink($file['path']);
                            }
                        }

                        unset($this->upfile[$att][$k]);
                    }
                }
            }
        }else if (empty($att) and !empty($k)){
            //删除某个文件
            foreach ($this->upfile as $att => $files){
                if ($files[$k]!==false){
                    if ($this->config['savedb']){
                        if ($f=File::findOne($files[$k]['id'])){
                            $f->delete();
                        }else if (is_file($files[$k]['path'])){
                            unlink($files[$k]['path']);
                        }
                    }else{
                        if (is_file($files[$k]['path'])){
                            unlink($files[$k]['path']);
                        }
                    }

                    unset($this->upfile[$att][$k]);
                }
            }
        }else if (!empty($att) and empty($k)){
            //删除某个文件
            foreach ($this->upfile[$att] as $k => $file){
                if ($file!==false){
                    if ($this->config['savedb']){
                        if ($f=File::findOne($file['id'])){
                            $f->delete();
                        }else if (is_file($file['path'])){
                            unlink($file['path']);
                        }
                    }else{
                        if (is_file($file['path'])){
                            unlink($file['path']);
                        }
                    }

                    unset($this->upfile[$att][$k]);
                }
            }
        }else if (!empty($att) and !empty($k)){
            if ($this->upfile[$att][$k]!==false){
                if ($this->config['savedb']){
                    if ($f=File::findOne($this->upfile[$att][$k]['id'])){
                        $f->delete();
                    }else if (is_file($this->upfile[$att][$k]['path'])){
                        unlink($this->upfile[$att][$k]['path']);
                    }
                }else{
                    if (is_file($this->upfile[$att][$k]['path'])){
                        unlink($this->upfile[$att][$k]['path']);
                    }
                }

                unset($this->upfile[$att][$k]);
            }
        }
    }

    /**
     * 给前台返回需要保存到数据库的文件id字符串
     * @param $att
     * @return bool|string
     */
    public function getIds($att){
        if ($this->upfile[$att]){
            $ids='';
            foreach ($this->upfile[$att] as $ks){
                if ($ks!==false){
                    $ids.=$ks['id'].',';
                }
            }
            return substr($ids,0,strlen($ids)-1);
        }
    }
}