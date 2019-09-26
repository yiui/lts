<?php

namespace backend\models;

use Yii;

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
class Article extends \common\models\Article
{


}
