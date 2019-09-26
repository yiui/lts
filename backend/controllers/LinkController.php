<?php

namespace backend\controllers;

use backend\models\Article;
use Yii;
use common\models\Link;
use common\models\LinkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\filters\AccessControl;
use common\models\JqUpload;
use common\models\Status;
use common\models\Pic;
/**
 * LinkController implements the CRUD actions for Link model.
 */
class LinkController extends Controller
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
     * Lists all Link models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LinkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 审核页面
     */
    public function actionAudi($id)
    {
        $model=$this->findModel($id);
        return $this->render('audi', [
            'model' => $model,
        ]);
    }


    /**
     * @param $car_id
     * @param $model
     * @return array
     * 初始化获取所有的图片Id
     */
    public function getAllPics($id){

        if ($link=$this->findModel($id)){
            $all_pic=Pic::findAll(['id'=>$link->pic_id]);
            return array_column($all_pic,'pic_id');
        }
        return [];
    }

    /**
     * Displays a single CarNewTurn model.
     * @param integer $id
     * @return mixed
     */
    public function actionAudiView($id)
    {
        $model=new JqUpload(['config'=>['skipOnEmpty'=>false,'maxFiles' => 10]]);
         $lp=new \common\models\LinkPic;
         $initPics=$lp->getAllPic($id);//$this->getAllPics($id);//初始图片ID数组

        return $this->renderAjax('audi-view',
            [
                'model'=>$model,
                'initPics'=>$initPics,
                'params'=>[
                    'cn'=>'LinkPic','iq'=>['link_id'=>$id]
                ]
            ]
        );
    }


    /**
     * Displays a single Link model.
     * @param string $id
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
     * Creates a new Link model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Link();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Link model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Deletes an existing Link model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionViewajax($id)
    {
        $profile=$this->findModel($id);
        $exp=1;
        return $this->renderAjax('view', [
            'model' => $profile,
            'exp'=>$exp,
        ]);
    }
    /**
     * Finds the Link model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Link the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Link::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('backend', 'The requested page does not exist.'));
    }

    //黑名单操作
    public function actionStatus(){
        if(Yii::$app->request->isPost){
            $id=yii::$app->request->post('id');
            $model=$this->findModel($id);
            $model->status=($model->status==0?1:0);
            if($model->save(false)){
                return Json::encode(['code'=>200,'message'=>'操作成功']);
            }

        }
        return Json::encode(['code'=>500,'message'=>'操作失败']);
    }
    /**
     * 审核某个内容为某个状态
     */
    public function actionSetOne($id,$value){
        if (!in_array($value,array_keys(Link::STATUS_ARRAY))){
            return '请求出错！';
        }
        //执行审核的业务模型
        $model=$this->findModel($id);//查找此ID的业务模型
        if( $model->status!=$value){
            // $model->scenario=Link::SCENARIO_ADMIN_UPDATE;
            $model->status=$value;
            if ($model->save(false)){
                return $this->redirect(['view','id'=>$id]);
            }else{
                throw new BadRequestHttpException('设置状态为'.Link::STATUS_ARRAY[$value].'失败');
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
                    if ($a=Link::findOne($id)){
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
        if (!is_numeric($status) or !in_array($status,array_keys(Link::STATUS_ARRAY))){
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
                    $model=Link::findOne($id);
                  //  $model->scenario=Link::SCENARIO_ADMIN_UPDATE;
                    //FriendLinks 没有场景，其他类可能需要
                    if ($model->status!=$status){
                        $model->status=$status;//数字状态，直接 =
                        if (!$model->save(false)){
                            $info[]=['id'=>$id,'status'=>1,'msg'=>'设为'.Article::STATUS_ARRAY[$status].'状态失败！'];
                        }
                    }
                }catch (\Exception $e){
                    $info[]=['id'=>$id,'status'=>$e->getCode(),'msg'=>'设为'.Link::STATUS_ARRAY[$status].'状态发生异常！'.$e->getMessage()];
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
