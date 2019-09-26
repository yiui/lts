<?php

namespace api\modules\v2\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property int $id 客户ID
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
        ];
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
