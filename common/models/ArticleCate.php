<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_cate".
 *
 * @property int $id ID
 * @property string $name 分类
 * @property int $status 状态
 * @property int $sort  排序
 */
class ArticleCate extends \yii\db\ActiveRecord
{


    const STATUS_ARRAY=[1=>'启用',0=>'禁用'];
    public $switchValues = [
        'status' => ['on' => 1, 'off' => 0],
        ];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article_cate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', '分类'),
            'status' => Yii::t('common', '状态'),
            'sort' => Yii::t('common', '排序'),
        ];
    }
}
