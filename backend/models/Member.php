<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property string $id 客户ID
 * @property string $name 姓名
 * @property int $sex 性别
 * @property int $birthday 出生年月
 * @property string $mobile 电话
 * @property string $email 邮箱
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sex', 'birthday'], 'integer'],
            [['name', 'mobile'], 'string', 'max' => 16],
            [['email'], 'string', 'max' => 50],
            ['email', 'check', 'on' => ['create']],//定义规则，在create场景中对children_id进行camp_idk方法验证，下面是方法的定义
//            ['name', 'unique',
//                'targetAttribute'=>['name','email'] ,
//                'targetClass' => 'Member',//默认当前
//                ],// 模型，缺省时默认当前模型。
//            'comboNotUnique' => '不同用户不能用一样的邮箱！' //错误信息

        ];
    }
    public function check($attribute, $params)
    {
        if (empty($this->email)) {
            return $this->addError($attribute, '请输入邮箱！');
        }
        $dish = Member::findOne(['name' => $this->name, 'email' => $this->email]);
        if ($dish) {
            $this->addError($attribute, '不同用户不能用一样的邮箱');
        } else {
            $this->clearErrors($attribute);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '客户ID',
            'name' => '姓名',
            'sex' => '性别',
            'birthday' => '出生年月',
            'mobile' => '电话',
            'email' => '邮箱',
        ];
    }
}
