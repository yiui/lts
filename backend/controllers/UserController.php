<?php

namespace backend\controllers;

use Yii;
use backend\models\User;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use abei2017\wx\Application;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new User model.
     * I发送邮件
     * @return mixed
     */
    public function actionEmail()
    {
        return $this->render('email');
    }


    public function actionSendEmail()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isPost) {
            $te_email = Yii::$app->request->post('email');
            $content = Yii::$app->request->post('content');
            if (empty($content)) {
                $content = '这是一封测试邮件';
            }
            $title = '来自LTS官方的邮件';
            $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";
            if (!preg_match($pattern, $te_email)) {
                return ['code' => 1, 'msg' => '请输入正确的邮箱'];
            }
            $mail = Yii::$app->mailer->compose();
            $mail->setTo($te_email);
            $mail->setSubject($title);
//$mail->setTextBody('zheshisha ');   //发布纯文字文本
            $mail->setHtmlBody("<div style='color:red;' >$content</div>");    //发布可以带html标签的文本
            if ($mail->send()) {
                return ['code' => 0, 'msg' => '发送成功'];
            } else {
                return ['code' => 1, 'msg' => '发送失败'];
            }
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    //获取用户opid

    /**
     * 微信登陆
     */
    public function actionWxlogin()
    {
        $scope = "snsapi_base";//静默授权
        $conf = Yii::$app->params['wx']['common'];
        $conf['oauth']['scopes'] = $scope;
        $wx = new Application(['conf' => $conf]);
        $oauth = $wx->driver('mp.oauth');

        $openid = $oauth->getOpenId();
        return $openid;
    }

}
