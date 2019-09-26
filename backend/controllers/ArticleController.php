<?php

namespace backend\controllers;

use Yii;
use backend\models\Article;
use backend\models\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => Yii::$app->params['web_cdn'],//图片访问路径前缀
                    "imagePathFormat" => "../../static/uploads/article/{yyyy}{mm}{dd}/{time}{rand:6}" //上传保存路径
                ],
            ]
        ];
    }
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

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
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
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();
        $model->scenario=Article::SCENARIO_ADMIN_CREATE;
        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            //当我们需要上传文件时，需要保证新上传的文件被引用，
            //剔除article中的图片

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $model->scenario=Article::SCENARIO_ADMIN_UPDATE;

        if ($model->load(Yii::$app->request->post())) {
            $data=\common\helpers\UeditorPic::getReomteImgToLOcal($model->content);
            //执行保存图片新图片，删除旧图片
$rs=\common\models\Article::contentPic($data['pics'],$model->id);
           $model->content=$data['content'];
            $model->file=UploadedFile::getInstance($model,'file');
            try{
                if($model->save()){
                    return $this->redirect(['view','id'=>$model->id]);
                }else{
                    //保存失败
                    $model->afterError();
                }
            }catch(\Exception $e){
                //保存失败
                $model->afterError();
                throw $e;
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        $model->scenario=$model::SCENARIO_ADMIN_UPDATE;
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }


    /**
     * 审核某个内容为某个状态
     */
    public function actionSetOne($id,$value){
        if (!in_array($value,array_keys(Article::STATUS_ARRAY))){
            return '请求出错！';
        }
        //执行审核的业务模型
        $model=$this->findModel($id);//查找此ID的业务模型
        if( $model->status!=$value){
            $model->status=$value;
            if ($model->save(false)){
                // $model->scenario=Link::SCENARIO_ADMIN_UPDATE;

                return $this->redirect(['view','id'=>$id]);
            }else{
                throw new BadRequestHttpException('设置状态为'.Article::STATUS_ARRAY[$value].'失败');
            }}else{
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * 删除所选
     * @return int
     */
    public function actionDelAll(){

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //验证
        if (!Yii::$app->request->isAjax or !Yii::$app->request->isPost){
            return ['status'=>400,'msg'=>'请求有误'];
        }
        if (!$allId=Yii::$app->request->post('ids') or !is_array($allId)){
            return ['status'=>400,'msg'=>'参数错误'];
        }

        $info=[];//操作的信息数组
        foreach ($allId as $id){
            //发生错误或异常返回具体的某个ID及其错误信息
            if (!is_numeric($id) or $id<=0){
                $info[]=['id'=>$id,'status'=>1,'msg'=>'参数错误!'];
            }else{
                try {
                    //我需要删除每一个文件，如果某个模型具有图片文件，必须使用此类删除
                    if ($a=\common\models\Article::findOne($id)){
                        //   $a->scenario=Link::SCENARIO_ADMIN_UPDATE;
                        if(!$a->delete()){
                            $info[]=['id'=>$id,'status'=>1,'msg'=>'删除失败！'];
                        }

                    }else{
                        $info[]=['id'=>$id,'status'=>1,'msg'=>'目标不存在！'];
                    }
                }catch (\Exception $e){
                    $info[]=['id'=>$id,'status'=>$e->getCode(),'msg'=>$e->getMessage()];
                }
            }
        }
        if ($info) {
            return $info;//返回具体的二维数组是告诉这次操作中其中的某个异常或错误的信息
        }else{
            return ['status' => 0, 'msg' => '全部操作成功'];//返回一维数组是告诉这次操作的整体信息
        }
    }

    /**
     * 设置所选
     * @return int
     */
    public function actionSetAll(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;//数据返回格式
        //验证
        if (!Yii::$app->request->isAjax or !Yii::$app->request->isPost){
            return ['status'=>400,'msg'=>'请求有误'];
        }
        if (!$allId=Yii::$app->request->post('ids') or !is_array($allId)){
            return ['status'=>400,'msg'=>'ID参数错误'];
        }
        $status=Yii::$app->request->post('status');
        if (!is_numeric($status) or !in_array($status,array_keys(Article::STATUS_ARRAY))){
            return ['status'=>400,'msg'=>'状态参数错误'];
        }

        $info=[];//操作的信息数组

        foreach ($allId as $id){
            //检查某个ID及其状态数值是否在状态下标的数组里
            if (!is_numeric($id) or $id<=0){
                $info[]=['id'=>$id,'status'=>1,'msg'=>'参数错误!'];
            }else{
                try {
                    //我需要删除每一个文件，如果某个模型具有图片文件，必须使用此类删除
                    $model=Article::findOne($id);
                    //  $model->scenario=Link::SCENARIO_ADMIN_UPDATE;
                    //FriendLinks 没有场景，其他类可能需要
                    if ($model->status!=$status){
                        $model->status=$status;//数字状态，直接 =
                        if (!$model->save(false)){
                            $info[]=['id'=>$id,'status'=>1,'msg'=>'设为'.Article::STATUS_ARRAY[$status].'状态失败！'];
                        }
                    }
                }catch (\Exception $e){
                    $info[]=['id'=>$id,'status'=>$e->getCode(),'msg'=>'设为'.Article::STATUS_ARRAY[$status].'状态发生异常！'.$e->getMessage()];
                }
            }
        }
        if ($info) {
            return $info;//返回具体的二维数组是告诉这次操作中其中的某个异常或错误的信息
        }else{
            return ['status' => 0, 'msg' => '全部操作成功'];//返回一维数组是告诉这次操作的整体信息
        }
    }
}
