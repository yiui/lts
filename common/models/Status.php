<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\caching\DbDependency;


/**
 * This is the model class for table "{{%status}}".
 *
 * @property integer $id
 * @property string $tb_name
 * @property string $value
 * @property integer $created_at
 *
 * @property Advert[] $adverts
 * @property Article[] $articles
 * @property ArticleComment[] $articleComments
 * @property CarBs[] $carBs
 * @property CarGold[] $carGolds
 * @property CarNew[] $carNews
 * @property CarNewBs[] $carNewBs
 * @property CarOld[] $carOlds
 * @property CarOldBs[] $carOldBs
 * @property CarParts[] $carParts
 * @property CarRepairProve[] $carRepairProves
 * @property File[] $files
 * @property Friend[] $friends
 * @property FriendLinks[] $friendLinks
 * @property Items[] $items
 * @property Post[] $posts
 * @property PostComment[] $postComments
 * @property Report[] $reports
 * @property ShopActi[] $shopActis
 * @property ShopGs[] $shopGs
 * @property Shops[] $shops
 * @property Spread[] $spreads
 * @property Tag[] $tags
 * @property User[] $users
 * @property UserConfig[] $userConfigs
 * @property UserIdcard[] $userIdcards
 * @property Wallet[] $wallets
 */
class Status extends \yii\db\ActiveRecord
{
    //格式统一，需要状态的地方辅复制各自的
    //默认状态
    const STATUS_DEFAULT = '正常';//默认状态请在某个表模型中自定义

    const STATUS_DOWNALL = '下架';//车子下架状态

    //冻结
    const STATUS_FREEZE = '冻结';
    //异常
    const STATUS_ERROR = '异常';
    //删除
    const STATUS_DELETE = '删除';
    //清除
    const STATUS_CLEAR = '清除';
    //未验证
    const STATUS_UNVERIFIED = '未验证';
    const STATUS_ISVERIFIED = '已验证';
    const STATUS_NOVERIFIED = '验证不过';
    //草稿
    const STATUS_DRAFT = '草稿';
    //审核状态
    const STATUS_AUDIT = '待审核';//多处使用
    const STATUS_NOPASS = '审核不过';
    const STATUS_PASS = '审核通过';
    //激活状态
    const STATUS_ACTIVE = '正常';
    //已发布
    const STATUS_RELEASE = '已发布';

    const STATUS_BEGIN = '已开始';
    const STATUS_END = '已完成';

    //-------------好友申请  使用
    //不同意Disagree 待同意t.b.a. 同意agree
    const STATUS_DISAGREE='不同意';
    const STATUS_TBAGREE='待同意';
    const STATUS_AGREE='同意';

    //------------某些设置、开关
    //不启用 启用
    const STATUS_ISUSE='启用';
    const STATUS_NOUSE='不启用';
    //不启用 启用
    const STATUS_ISOK='可用';
    const STATUS_NOOK='不可用';

    //-------------消息 使用
    //未读 已读
    const STATUS_ISREAD='已读';
    const STATUS_NOREAD='未读';

    //--------------举报 使用
    //无效invalid 待处理pending 已处理processed
    const STATUS_INVALID='无效';
    const STATUS_PENDING='待处理';
    const STATUS_PROCESSED='已处理';

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
        return '{{%status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tb_name', 'value'], 'required'],
            [['value','tb_name'], 'filter', 'filter' => function ($value) {
                if (empty($value)){
                    return null;
                }
                return \common\helpers\Str::purify($value);
            }],
            [['tb_name', 'value'], 'string','min'=>2, 'max' => 32],
            ['created_at', 'integer'],
            ['tb_name', 'match', 'pattern' => '/^[a-z]\w*$/i','message'=>'表名应该以小写字母开头，可用数字结尾'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'tb_name' => Yii::t('common', 'Tb Name'),
            'value' => Yii::t('common', 'Value'),
            'created_at' => Yii::t('common', 'Created At'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['status_id' => 'id']);
    }


    //所有状态,KEY就是状态ID，值是此ID键对应的值
    //取状态值应：allStatus[$this->status_id]['value']
    public static function all($tabName)
    {
        $my_all_status=Yii::$app->memCache->get($tabName.'.all.status');
        if ($my_all_status === false){
            if ($data=self::find()->select('id,value')->where(['tb_name'=>$tabName])->asArray()->all()){
                $my_all_status= ArrayHelper::map($data,'id','value');
                if (Yii::$app->params['data_cache']){
                    $dep=new DbDependency(['sql'=>'SELECT MAX(`created_at`) FROM '.self::getTableSchema()->name.' WHERE `tb_name`=:tbname','params'=>[':tbname'=>$tabName]]);
                    Yii::$app->memCache->set($tabName.'.all.status',$my_all_status,0,$dep);
                }
            }else{
                $my_all_status=[];
            }
        }
        //获取所有状态,KEY就是状态ID，值是此ID键下的 id,value 数组
        return $my_all_status;
    }

    /**
     * 获取所有状态ID数组
     * 用于判断状态值是否在此数组里
     * @return array|bool
     */
    public static function allId($tabName){
        $all=self::find()->select('id')->where(['tb_name'=>$tabName])->indexBy('id')->asArray()->all();
        if (!$all){
            return [];
        }

        $statusid=array();

        foreach ($all as $k => $v){
            $statusid[]=$k;
        }

        return $statusid;
    }

    /**
     * 将状态值转换成ID
     * @param $value 状态值字符串
     * @return int|bool 状态ID
     */
    public static function v2I($tabName,$value){
        $status_id=Yii::$app->memCache->get('status.'.$tabName.$value);
        if ($status_id === false){
            if ($status = self::find()->select('id')->where(['tb_name' => $tabName, 'value' => $value])->limit(1)->one()) {
                $status_id=$status->id;
                if (Yii::$app->params['data_cache']) {
                    Yii::$app->memCache->set('status.' . $tabName . $value, $status_id, 3600);
                }
            }else{
                $status_id=null;
            }
        }
        return $status_id;
    }

    /**
     * 返回某个状态ID对应的值
     * @param $status_id
     * @return mixed|null
     */
    public static function i2V($status_id){
        $status_v=Yii::$app->memCache->get('status.'.$status_id);
        if ($status_v === false){
            if ($status=self::find()->select('value')->where(['id'=>$status_id])->limit(1)->one()){
                $status_v = $status->value;
                if (Yii::$app->params['data_cache']) {
                    Yii::$app->memCache->set('status.' . $status_id, $status_v, 3600);
                }
            }else{
                $status_v=null;
            }
        }
        return $status_v;
    }

    public function beforeSave($insert)
    {
        if ($insert){
            //是否已经添加
            if (self::find()->select('id')->where(['value'=>$this->value,'tb_name'=>$this->tb_name])->limit(1)->one()){
                $this->addError('value','此目标状态已经添加！');
                return false;
            }
        }else{
            if ($this->value!=$this->oldAttributes['value'] or $this->tb_name!=$this->oldAttributes['tb_name']){
                //是否已经添加
                if (self::find()->select('id')->where(['value'=>$this->value,'tb_name'=>$this->tb_name])->limit(1)->one()){
                    $this->addError('value','此目标状态已经添加！');
                    return false;
                }
            }
        }

        $this->created_at = time();

        //如果不返回真假判断会导致无法保存
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
