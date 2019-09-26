<?php

namespace backend\controllers;

use Yii;
use backend\models\ArticleCate;
use backend\models\ArticleCateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use common\helpers\Str;
/**
 * ArticleCateController implements the CRUD actions for ArticleCate model.
 */
class ArticleCateController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
//            'toggle-extended' => [
//                'class' => ToggleAction::class,
//                'modelClass' => 'common\models\ArticleCate', // Your model class,
//                'pkColumn' => 'id', // default 'id'
//            ],
        ];
    }

    /**
     * Lists all ArticleCate models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = ArticleCate::findOne($id);
            $out = ['output'=>'', 'message'=>''];
            $posted = current($_POST[Str::ControllerName(Yii::$app->controller->id)]);
            $post = [Str::ControllerName(Yii::$app->controller->id) => $posted];
            if($model->load($post)&&$model->save(false)){
                $out = ['output'=>'', 'message'=>''];
            }else{
                $out['message'] = $model->getErrors();
            }
            echo Json::encode($out);
            return;
        }

        $searchModel = new ArticleCateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ArticleCate model.
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
     * Creates a new ArticleCate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ArticleCate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ArticleCate model.
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
     * Deletes an existing ArticleCate model.
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


    //状态调整
    public function actionStatus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;//数据返回格式
        if (Yii::$app->request->isGet) {
            $id = Yii::$app->request->get('id');
            $model = $this->findModel($id);
            $model->status = $model->status == 0 ? 1 : 0;
            if ($model->save(false)) {
                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        return $this->redirect(['site/error', 'tip' => '操作失败,请重试']);

    }

    /**
     * Finds the ArticleCate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ArticleCate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ArticleCate::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }


    //字段状态修改
    public function actionToggleAndSend()
    {
        var_dump(Yii::$app->request->getBodyParams());
        exit;
    }
}
