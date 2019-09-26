<?php

namespace backend\controllers;

use Yii;
use common\models\Pic;
use common\models\PicSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\JqUpload;

/**
 * PicController implements the CRUD actions for Pic model.
 */
class PicController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [//允许的动作
                    'delete' => ['POST'],
                    'file-up' => ['POST'],
                    'file-del' => ['POST'],
                ],
            ],
        ];
    }

    public function actions(){
        return [
            'file-up' => 'common\actions\JqUpAction',//文件上传
            'file-del' => 'common\actions\JqDelAction',//文件删除
        ];
    }

    /**
     * Lists all Pic models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CarNewTurn model.
     * @param integer $id
     * @return mixed
     */
    public function actionAudi($car_id)
    {
        $model=new JqUpload(['config'=>['skipOnEmpty'=>false,'maxFiles' => 10]]);

        $initPics=CarBs::getAllPics($car_id,new CarBsTurn());//初始图片ID数组

        return $this->renderAjax('audi',
            [
                'model'=>$model,
                'initPics'=>$initPics,
                'params'=>[
                    'cn'=>'CarBsTurn','iq'=>['car_id'=>$car_id]
                ]
            ]
        );
    }
    /**
     * Displays a single Pic model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Pic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pic();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Pic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Pic model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pic::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }
}
