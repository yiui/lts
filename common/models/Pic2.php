<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pic2".
 *
 * @property string $id ID
 * @property string $path 路径
 *
 * @property Article[] $articles
 */
class Pic2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pic2';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'path' => Yii::t('common', '路径'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['pic_id' => 'id']);
    }
}
