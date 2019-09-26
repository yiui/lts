<?php

namespace common\models;

use Yii;
use yii\web\ForbiddenHttpException;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property integer $id
 * @property integer $item_id
 * @property string $info
 * @property string $path
 * @property integer $user_id
 * @property integer $size
 * @property integer $times
 * @property integer $status_id
 * @property integer $created_at
 *
 * @property Items $item
 * @property Status $status
 * @property User $user
 */
class File extends \yii\db\ActiveRecord
{
    //类型
    const ITEM_DEFAULT='用户文件';
    const ITEM_USER_FILE='用户文件';
    const ITEM_USER_TX='用户头像';
    const ITEM_USER_IMG='用户图片';
    const ITEM_COMMOM_FILE='公共文件';
    const ITEM_COMMOM_IMG='公共图片';
    const ITEM_BACKUPS_FILE='备份文件';
    const ITEM_BACKUPS_IMG='备份图片';

    const STATUS_DEFAULT = '已发布';//默认状态请在某个表模型中自定义
    const STATUS_ACTIVE='已发布';

    //场景
    const SCENARIO_USER_CREATE='0';//前台用户新增
    const SCENARIO_USER_UPDATE='1';//前台用户更新
    const SCENARIO_ADMIN_CREATE='2';//后台管理员新增
    const SCENARIO_ADMIN_UPDATE='3';//后台管理员更新

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'user_id', 'times','size', 'status_id', 'created_at'], 'integer'],
            [['path'], 'required'],
            [['info','path'], 'filter', 'filter' => function ($value) {
                if (empty($value)){
                    return null;
                }
                return \common\helpers\Str::purify($value);
            }],
            [['info', 'path'], 'string', 'max' => 256],
            [['path'], 'unique'],
            //[['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Items::className(), 'targetAttribute' => ['item_id' => 'id']],
            //[['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'id']],
            ['status_id', 'default', 'value' => Status::v2I(self::getTableSchema()->name, self::STATUS_DEFAULT)],
            //['status_id', 'in', 'range' => Status::allId(self::getTableSchema()->name)],//这句跟上面exist查询选择一个即可
            ['status_id', 'in', 'range' => array_keys(Status::all(self::getTableSchema()->name))],//必须在此状态数组里
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            ['item_id', 'default', 'value' => Items::v2I(self::getTableSchema()->name, self::ITEM_DEFAULT)],
            ['item_id','in','range'=>array_keys(Items::all(self::getTableSchema()->name))],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'item_id' => Yii::t('common', 'Item ID'),
            'info' => Yii::t('common', 'Info'),
            'path' => Yii::t('common', 'Path'),
            'user_id' => Yii::t('common', 'User ID'),
            'times' => Yii::t('common', 'Times'),
            'size' => Yii::t('common', 'Size'),
            'status_id' => Yii::t('common', 'Status ID'),
            'created_at' => Yii::t('common', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['pic_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Items::className(), ['id' => 'item_id']);
    }

    /**
     * 直接在缓存中获取分类名，注意是分类的 name
     * Created by www.yiui.top.
     * User: Zhao Wenming
     * @return null
     */
    public function getItemName()
    {
        if (is_numeric($this->item_id)){
            $all_items=Items::all(self::getTableSchema()->name);
            if (isset($all_items[$this->item_id])){
                return $all_items[$this->item_id];
            }elseif($item=$this->item){
                return $item->name;
            }
        }

        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * 直接在缓存中获取状态名，注意是状态的 value
     * Created by www.yiui.top.
     * User: Zhao Wenming
     * @return null
     */
//    public function getStatusName()
//    {
//        if (is_numeric($this->status_id)){
//            $all_status=Status::all(self::getTableSchema()->name);
//            if (isset($all_status[$this->status_id])){
//                return $all_status[$this->status_id];
//            }elseif($status=$this->status){
//                return $status->value;
//            }
//        }
//
//        return null;
//    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * 获取文件网址
     * @return string
     */
    public function getUrl(){
        return Yii::$app->params['web_cdn'].$this->path;
    }

    /**
     * 删除数据库内容和实际文件
     * @return bool
     */
    public function delete(){
        if (parent::delete()){
            //basePath只是项目根目录，返回上一级即可
            $filename=Config::STATIC_DIR_PATH.str_replace('/', DIRECTORY_SEPARATOR,$this->path);
            if (is_file($filename)){
                return unlink($filename);
            }
        }

        return false;
    }

    /**
     * 通过路径或者ID删除
     * @param $pathorid
     */
    public function deletePathOrId($pathorid){
        if (is_numeric($pathorid)){
            self::findOne($pathorid)->delete();
        } else if (strpos($pathorid,',')){
            $file=self::find()->select('id')->where(['in','id',$pathorid])->all();
            foreach ($file as $f) {
                $f->delete();
            }
        }else{
            if (is_file(Config::STATIC_DIR_PATH.str_replace('/', DIRECTORY_SEPARATOR,$pathorid))){
                unlink(Config::STATIC_DIR_PATH.str_replace('/', DIRECTORY_SEPARATOR,$pathorid));
            }
        }
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            if (empty($this->item_id)) {
                $this->item_id = Items::v2I(self::getTableSchema()->name, self::ITEM_DEFAULT);
            }
            if (empty($this->user_id)){
                $this->user_id=Yii::$app->user->id;
            }
            if (empty($this->status_id)) {
                $this->status_id = Status::v2I(self::getTableSchema()->name, self::STATUS_DEFAULT);
            }

            if ($this->scenario==self::SCENARIO_USER_CREATE){
                $this->user_id=Yii::$app->user->id;
                $this->status_id = Status::v2I(self::getTableSchema()->name, self::STATUS_DEFAULT);
            }
        }else{
            if ($this->scenario==self::SCENARIO_USER_UPDATE){
                //原状态是否是冻结
                if ($this->oldAttributes['status_id'] == Status::v2I(self::getTableSchema()->name, Status::STATUS_FREEZE)){
                    throw new ForbiddenHttpException('已被管理员冻结，您无权进行任何操作！');
                }
            }
        }
        $this->created_at = time();

        //如果不返回真假判断会导致无法保存
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes){
		parent::afterSave($insert, $changedAttributes);
		
        //删除之前的图片(保存之前只有通过 变化的属性获得之前的值)
        if (isset($changedAttributes['path']) and !empty($changedAttributes['path']) and $changedAttributes['path']!=$this->path){
            $filename=Config::STATIC_DIR_PATH.str_replace('/', DIRECTORY_SEPARATOR,$changedAttributes['path']);
            if (is_file($filename)){
                unlink($filename);
            }
        }
    }

    /**
     * 在插入或更新时发生错误或异常之后，请执行此操作
     * 特别是在上传图片成功之后，但却保存失败的时候，需要删除新的图片
     * Created by www.yiui.top.
     * User: Zhao Wenming
     */
    public function afterError(){
        //保存失败，删除刚上传的图片，如果刚刚上传了图片，新图片跟旧图不相等
        if (!empty($this->path) and ((isset($this->oldAttributes['path']) and $this->path!=$this->oldAttributes['path']) or !isset($this->oldAttributes['path']))){
            $filename=Config::STATIC_DIR_PATH.str_replace('/', DIRECTORY_SEPARATOR,$this->path);
            if (is_file($filename)){
                unlink($filename);
            }
        }
    }

    public function beforeDelete()
    {
        if ($this->scenario!=self::SCENARIO_ADMIN_UPDATE){
            if ($this->user_id!=Yii::$app->user->id){
                throw new ForbiddenHttpException('不是您的数据，您无权删除！');
            }
        }
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }
}
