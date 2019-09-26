<?php
namespace api\controllers;

use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use common\models\Article;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use common\models\Adminuser;
use yii\filters\auth\HttpBasicAuth;

class ArticleController extends ActiveController
{
    public $modelClass = 'common\models\Article';
    
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticatior' => [
                'class' => QueryParamAuth::className()
            ]
        ]);
    }    
//     public function behaviors()
//     {
//          return ArrayHelper::merge(parent::behaviors(), [
//             'authenticatior' => [
//                 'class' => HttpBasicAuth::className(),
//                 'auth' => function ($username, $password) {              
//                         $user = Adminuser::find()->where(['username' => $username])->one();
//                         if ($user->validatePassword($password)) {
//                             return $user;
//                         }
//                         return null;
//                         }
//                      ]
//             ]);
//     }
   
    public  function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }
    public function actionIndex()
    {
        $modelClass = $this->modelClass;
        return new ActiveDataProvider(
                [
                    'query'=>$modelClass::find()->asArray(),
                    'pagination'=>['pageSize'=>5],
                ]
            );
    }
    
    public function actionSearch() {
        return Article::find()->where(['like','title',$_POST['keyword']])->all();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}