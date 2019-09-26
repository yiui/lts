<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "article_pic".
 *
 * @property string $id id
 * @property int $article_id
 * @property string $path 图片
 */
class ArticlePic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article_pic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id'], 'integer'],
            [['path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'article_id' => 'Article ID',
            'path' => '图片',
        ];
    }
}
