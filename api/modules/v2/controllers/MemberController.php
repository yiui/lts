<?php
namespace api\modules\v2\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\filters\Cors;
use yii\filters\RateLimiter;//速率限制

class MemberController extends ActiveController
{
    public $modelClass = 'api\modules\v2\models\Member';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = '';
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        $behaviors['rateLimiter'] = [//速率限制
            'class' => RateLimiter::className(),
            'enableRateLimitHeaders' => true,
        ];
        $behaviors= ArrayHelper::merge([
            'corsFilter' =>[
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => Yii::$app->params['apiOrigin'],//定义允许来源的数组
                    'Access-Control-Request-Method' => ['GET','POST','PUT','PATCH','DELETE','OPTIONS'],//允许动作的数组
                    'Access-Control-Request-Headers' => ['*'],
                ],
            ]
        ],$behaviors);
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
           // 'except' =>['index','update','create','delete']
        ];
        return $behaviors;
    }

/**
 *
 * 查询所有GET
 * http://lts_api.de/v2/members
 * 查询指定记录GET
 * http://lts_api.de/v2/members/1
 * 增加记录
 *http://lts_api.de/v2/members
 *Content-Type  application/json
{
"name": "anner",
"sex": 0,
"birthday": 1324234,
"mobile": "13213345678",
"email": "anner@123.com"
}
 * 修改记录
 *http://lts_api.de/v2/members/5
 * {
"name": "jack",
"sex": 1,
"birthday": 1324234,
"mobile": "13212345678",
"email": "swe@123.com"
}
 * 删除记录DELETE
 * http://lts_api.de/v2/members/3
 *
 *
 **/












}