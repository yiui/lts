<?php

namespace common\models;

use Yii;
use common\models\ArticleCate;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article".
 *
 * @property int $id ID
 * @property int $user_id 作者
 * @property string $title 标题
 * @property string $description 描述
 * @property string $content 内容
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property int $read_num 阅读次数
 * @property string $sourse 来源
 * @property int $pic_id 封面
 * @property int $cate_id 分类
 * @property int $good_num 赞
 * @property int $bads_num 贬
 *
 * @property ArticleCate $cate
 * @property User $user
 */
class Article extends \yii\db\ActiveRecord
{

    const STATUS_ARRAY = [0 => '待审核', 1 => '审核通过', 2 => '审核失败'];
    const SCENARIO_ADMIN_CREATE = '2';//后台管理员新增
    const SCENARIO_ADMIN_UPDATE = '3';//后台管理员更新
    public $file;//接受上传文件

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'updated_at', 'read_num', 'cate_id', 'good_num', 'bads_num', 'status'], 'integer'],
            [['description', 'content'], 'required'],
            [['description', 'content'], 'string'],
            [['title'], 'string', 'max' => 200],
            [['sourse'], 'string', 'max' => 255],
            [['cate_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleCate::className(), 'targetAttribute' => ['cate_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            //使用过滤器
            UploadImg2::rules('file', ['skipOnEmpty' => true]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'user_id' => Yii::t('common', '作者'),
            'title' => Yii::t('common', '标题'),
            'description' => Yii::t('common', '描述'),
            'content' => Yii::t('common', '内容'),
            'created_at' => Yii::t('common', '创建时间'),
            'updated_at' => Yii::t('common', '更新时间'),
            'read_num' => Yii::t('common', '阅读次数'),
            'sourse' => Yii::t('common', '来源'),
            'file' => Yii::t('common', 'Pic ID'),
            'cate_id' => Yii::t('common', '分类'),
            'good_num' => Yii::t('common', '赞'),
            'bads_num' => Yii::t('common', '贬'),
            'status' => Yii::t('common', '状态'),
        ];
    }

    //场景配置
    public function scenarios()
    {
        //也使用默认的场景，不然默认场景将无法使用
        $scenarios = parent::scenarios();
        //添加自己的场景
        $scenarios[self::SCENARIO_ADMIN_CREATE] = [
            'title', 'description', 'status', 'file', 'read_num', 'sourse', 'cate_id', 'content', 'goods', 'bads', 'user_id'
        ];
        //后台更新场景
        $scenarios[self::SCENARIO_ADMIN_UPDATE] = [
            'title', 'description', 'status', 'file', 'read_num', 'sourse', 'cate_id', 'content', 'goods', 'bads', 'user_id'
        ];
        //也可以只返回我们自己的场景，这时候默认场景等将无法使用
        return $scenarios;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(ArticleCate::className(), ['id' => 'cate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPic2()
    {
        return $this->hasOne(Pic2::className(), ['id' => 'pic_id']);
    }

    //查询关联的id-name
    //所有状态,KEY就是状态ID，值是此ID键对应的值
    //取状态值应：allStatus[$this->status_id]['value']
    public static function all()
    {
        if ($data = ArticleCate::find()->select('id,name')->asArray()->all()) {
            $my_all_status = ArrayHelper::map($data, 'id', 'name');
            //获取所有状态,KEY就是状态ID，值是此ID键下的 id,name 数组
            return $my_all_status;
        }
    }

    /**
     * 在文章删除之后，更新标签
     * 文章删除后，下级文章的父id默认为空
     */
    public function afterDelete()
    {
//        parent::afterDelete();
//        Tag::updateFrequency($this->tag, '');

        //删除图片
        if (!empty($this->pic_id)) {
            if ($f = Pic2::findOne($this->pic_id)) {
                $f->delete();
            }
        }
    }

    /**
     * 在插入或更新时发生错误或异常之后，请执行此操作
     * 特别是在上传图片成功之后，但却保存失败的时候，需要删除新的图片
     */
    public function afterError()
    {
        //保存失败，删除刚上传的图片，如果刚刚上传了图片，新图片跟旧图不相等
        if (!empty($this->pic_id) and $this->pic_id != $this->oldAttributes['pic_id']) {
            if ($p = Pic2::findOne($this->pic_id)) {
                $p->delete();
            }
        }
    }

//区分场景进行前置后置操作
    public function beforeSave($insert)
    {
        if ($insert) {
            if ($this->scenario == self::SCENARIO_ADMIN_CREATE) {
                if (empty($this->user_id)) {
                    $this->user_id = Yii::$app->user->id;
                }
                $this->status = 0;
                $this->created_at = time();
                $this->updated_at = time();
                if (!isset($this->read_num)) {
                    $this->read_num = 0;
                }


            } else if ($this->scenario == self::SCENARIO_ADMIN_UPDATE) {
                $this->updated_at = time();
            }
            //图片处理
            $up = new UploadImg2();//上传和处理并可获得ID等更多信息，自动赋值给模型需要的属性，和报错
            if ($up->up($this, 'file', 'pic_id', ['th_width' => 150, 'th_height' => 100]) === false) {
                return false;
            }

        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }


    /**
     * 增删content图片
     * @return null
     */
    public static function contentPic($pics, $id = null)
    {
        if (!is_array($pics)) {
            return null;
        }
//批量删除
        if (!empty($id)) {
            $res = ArticlePic::find()->where(['article_id' => $id])->all();
            foreach ($res as $k => $v) {
                @unlink('../../static/' . $v['path']);
            }
            ArticlePic::deleteAll(['article_id' => $id]);
        }

//批量增加
        foreach ($pics as $vv) {
            $user = new ArticlePic();
            $user->article_id = $id;
            $user->path = $vv;
            $user->save();
        }
    }


}