<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id 自增ID
 * @property string $username 用户名
 * @property string $auth_key 自动登陆key
 * @property string $password_hash 加密密码
 * @property string $password_reset_token 重置密码token
 * @property string $email 邮箱
 * @property int $role 角色等级
 * @property int $status 用户状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property Article[] $articles
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['role', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', '自增ID'),
            'username' => Yii::t('backend', '用户名'),
            'auth_key' => Yii::t('backend', '自动登陆key'),
            'password_hash' => Yii::t('backend', '加密密码'),
            'password_reset_token' => Yii::t('backend', '重置密码token'),
            'email' => Yii::t('backend', '邮箱'),
            'role' => Yii::t('backend', '角色等级'),
            'status' => Yii::t('backend', '用户状态'),
            'created_at' => Yii::t('backend', '创建时间'),
            'updated_at' => Yii::t('backend', '更新时间'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['user_id' => 'id']);
    }
}
