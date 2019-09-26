<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\filters\Cors;
use api\modules\v2\models\Link;
use yii\data\Pagination;
use yii\filters\RateLimiter;//速率限制

class LinkController extends ActiveController
{
    public $modelClass = 'api\modules\v2\models\Link';

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
            //    'except' =>['index','update','create','delete']
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
     * 友情链接
     * @SWG\Get(
     *      path="/link",
     *      tags={"link链接"},
     *      summary="链接列表",
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Response(
     *         response = 200,
     *         description = ""
     *      ),
     *     @SWG\Parameter(
     *        in = "header",name = "Authorization",description = "Token,Bearer模式",required = true,type = "string"
     *     ),
     *     @SWG\Parameter(
     *        in = "query",
     *        name = "page",
     *        description = "当前页",
     *        type = "integer",
     *     )
     *  )
     */
    public function actionIndex()
    {
        $page = Yii::$app->get('page', 0);
        $modelClass = $this->modelClass;
        return new ActiveDataProvider(
            [
                'query' => $modelClass::find()->asArray(),
                'pagination' => ['pageSize' =>10, 'page' => $page],
            ]
        );
    }

    /**
     * 友情链接
     * @SWG\Patch(
     *      path="/link/update",
     *      tags={"link链接"},
     *      summary="链接更新",
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Response(
     *         response = 200,
     *         description = ""
     *      ),
     *     @SWG\Parameter(
     *        in = "header",name = "Authorization",description = "Token,Bearer模式",required = true,type = "string"
     *     ),
     *     @SWG\Parameter(
     *        in = "formData",
     *        name = "id",
     *        description = "id",
     *        type = "integer",
     *     ),
     *     @SWG\Parameter(
     *        in = "formData",
     *        name = "title",
     *        description = "标题",
     *        type = "string",
     *     ),
     *     @SWG\Parameter(
     *        in = "formData",
     *        name = "url",
     *        description = "链接",
     *        type = "string"
     *     ),
     *     @SWG\Parameter(
     *        in = "formData",
     *        name = "status",
     *        description = "状态,1:开启，0:关闭",
     *        type = "integer",
     *        enum={0,1},
     *        default=1
     *     )
     *  )
     */
    public function actionUpdate()
    {
        $id = Yii::$app->request->getBodyParam('id', 0);
        $model = Link::findOne($id);
        // if($model->load(Yii::$app->request->getBodyParams(), '')&&$model->save()){
        if ($model->load(Yii::$app->request->getBodyParams(), '') && $model->save()) {
            return ['code' => 0, 'msg' => 'success'];
        }
        return ['code' => 1, 'msg' => current($model->getFirstErrors())];
    }

    /**
     * 友情链接
     * @SWG\Post(
     *      path="/link/create",
     *      tags={"link链接"},
     *      summary="链接增加",
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Response(
     *         response = 200,
     *         description = ""
     *      ),
     *     @SWG\Parameter(
     *        in = "header",name = "Authorization",description = "Token,Bearer模式",required = true,type = "string"
     *     ),
     *     @SWG\Parameter(
     *        in = "formData",
     *        name = "title",
     *        description = "标题",
     *        type = "string",
     *     ),
     *     @SWG\Parameter(
     *        in = "formData",
     *        name = "url",
     *        description = "链接",
     *        type = "string"
     *     ),
     *     @SWG\Parameter(
     *        in = "formData",
     *        name = "status",
     *        description = "状态,1:开启，0:关闭",
     *        type = "integer",
     *        enum={0,1},
     *        default=1
     *     )
     *  )
     */
    public function actionCreate()
    {
        $model = new Link();
        // if($model->load(Yii::$app->getRequest()->getBodyParams(), '')&&$model->save()){
        if ($model->load(Yii::$app->request->getBodyParams(), '') && $model->save()) {
            return ['code' => 0, 'msg' => 'success'];
        }
        return ['code' => 1, 'msg' => current($model->getFirstErrors())];

    }


    /**
     * 友情链接
     * @SWG\Delete(
     *      path="/link/delete",
     *      tags={"link链接"},
     *      summary="链接删除",
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Response(
     *         response = 200,
     *         description = ""
     *      ),
     *     @SWG\Parameter(
     *        in = "header",name = "Authorization",description = "Token,Bearer模式",required = true,type = "string"
     *     ),
     *     @SWG\Parameter(
     *        in = "query",
     *        name = "id",
     *        description = "id",
     *        type = "integer",
     *     )
     *  )
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->getQueryParam('id');
        $model = Link::findOne($id);
        if ($model&&$model->delete()) {
            return ['code' => 0, 'msg' => 'success'];
        }
        return ['code' => 1, 'msg' => 'error'];
    }


}