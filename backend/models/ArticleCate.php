<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_cate".
 *
 * @property int $id ID
 * @property string $name 分类
 * @property int $status 状态
 * @property int $sort 状态
 */
class ArticleCate extends \common\models\ArticleCate
{
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
            [['status','sort'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['status','name'],'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', '分类'),
            'status' => Yii::t('backend', '状态'),
            'sort' => Yii::t('backend', '排序'),
        ];
    }
}
