<?php
namespace api\modules\v2\controllers;

use yii;
use yii\rest\ActiveController;
use api\models\ApiLoginForm;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\filters\Cors;
use yii\filters\RateLimiter;//速率限制

class AdminuserController extends ActiveController
{
    public $modelClass = 'api\models\Adminuser';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = '';
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        $behaviors['rateLimiter'] = [//速率限制
            'class' => RateLimiter::className(),
            'enableRateLimitHeaders' => true,
        ];
        $behaviors = ArrayHelper::merge([
            'corsFilter' => [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => Yii::$app->params['apiOrigin'],//定义允许来源的数组
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],//允许动作的数组
                    'Access-Control-Request-Headers' => ['*'],
                ],
            ]
        ], $behaviors);
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
            'except' =>['login']
        ];
        return $behaviors;
    }


    public function actions()
    {
        $actions = parent::actions();
        // 禁用index操作
        unset($actions['index'], $actions['update'], $actions['delete'], $actions['create']);
        return $actions;
    }
    /**
     * 登录登录
     *  @SWG\Post(
     *      path="/adminuser/login",
     *      tags={"adminuser登录"},
     *      summary="用户登录",
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Response(
     *         response = 200,
     *         description = ""
     *      ),
     *      @SWG\Parameter(
     *        in = "formData",
     *        name = "username",
     *        description = "用户名",
     *        required = true,
     *        type = "string"
     *      ),
     *      @SWG\Parameter(
     *        in = "formData",
     *        name = "password",
     *        description = "密码",
     *        required = true,
     *        type = "string"
     *      ),
     *  )
     */
    public function actionLogin()
    {
        $model = new ApiLoginForm();
        
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        
//         $model->username = $_POST['username'];
//         $model->password = $_POST['password'];
        
        if ($model->login()) {
            return ['access_token' => $model->login()];
        }
        else {
            $model->validate();
            return $model;
        }
        
    }
    
    
}