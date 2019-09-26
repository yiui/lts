<?php
namespace api\controllers;

use yii\rest\Controller;
use common\models\Article;
use yii\db\Query;

class Top10Controller extends Controller
{
    public function actionIndex()
    {
        $top10 = (new Query())
            ->from('article')
            ->select(['created_at','title'])
            ->groupBy(['created_at'])
            ->orderBy('id DESC')
            ->limit(10)
            ->all();
        
        return $top10;
    }
 
}