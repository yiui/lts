<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;


Yii::$app->response->format=Response::FORMAT_JSON;
Yii::$app->request->enableCsrfValidation=false;

class SiteController extends Controller
{


    /**
     * @SWG\Get(
     *     path="/site/list",
     *     tags={"水果列表"},
     *     summary="获取水果列表",
     *     description="返回一个数组",
     *     produces={"application/json"},
     *
     *     @SWG\Response(
     *         response = 200,
     *         description = ""
     *     )
     * )
     *
     */
    public function actionList()
    {
        return ['code'=>200,'list'=>['苹果','香蕉']];
    }
}
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
