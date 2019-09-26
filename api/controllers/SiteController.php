<?php
namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\helpers\Url;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'actions' => ['login', 'error'],
//                        'allow' => true,
//                    ],
//                    [
//                        'actions' => ['logout', 'index'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//            'verbs' => [
//                'class' => VerbFilter::className(),
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
//        ];
//    }

    public function actions()
    {
        return [
            'doc' => [
                'class' => 'light\swagger\SwaggerAction',
                'restUrl' => Url::to(['site/v1'], true),//跳转
            ],
            'v1' => [
                'class' => 'light\swagger\SwaggerApiAction',
                'scanDir' => [
                    Yii::getAlias('@api/modules/v1/swagger/swagger.php'),
                    Yii::getAlias('@api/modules/v1/controllers'),
                    //Yii::getAlias('@api/controllers'),
                ],
              //  'api_key' => 'test',
               // 'cache'=>'cache',
                'cacheKey'=>'api-swagger-cache'
            ],
            'v2' => [
                'class' => 'light\swagger\SwaggerApiAction',
                'scanDir' => [
                    Yii::getAlias('@api/modules/v2/swagger/swagger.php'),
                    Yii::getAlias('@api/modules/v2/controllers'),
                    //Yii::getAlias('@api/controllers'),
                ],
                //  'api_key' => 'test',
                // 'cache'=>'cache',
                'cacheKey'=>'api-swagger-cache'
            ],
        ];
    }
//    /**
//     * @inheritdoc
//     */
//    public function actions()
//    {
//        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
//        ];
//    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionList()
    {
        return ['code'=>200,'list'=>['杭州','福州','宁波','舟山','安阳','萍乡']];
    }
}
