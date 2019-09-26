<?php
namespace api\controllers;

use yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use api\models\ApiLoginForm;


class AdminuserController extends ActiveController
{
    public $modelClass = 'common\models\Adminuser';




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