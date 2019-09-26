<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'language' => 'zh-CN',//配置语言
    'timeZone' => 'PRC',//默认时区
    'name' => 'LTS后台',//应用名称
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            // 'layout' => 'left-menu',//yii2-admin的导航菜单，词句调用yii2-admin的样式对lte样式进行覆盖
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
            //您的其他网格模块设置
        ],
    ],
    "aliases" => [
        "@mdm/admin" => "@vendor/mdmsoft/yii2-admin",
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        // 'allowActions' => ['*'], // 后面对权限完善了以后，记得把*改回来！
    ],
    //'defaultRoute' => 'site/index',//设置默认主页
    'components' => [
        "authManager" => [
            "class" => 'yii\rbac\DbManager',
            "defaultRoles" => ["guest"],
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            // 'loginUrl' => array('/rbac/user/login'),//登陆页
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
//        'view' => [
//            'theme' => [
//                'pathMap' => [
//                    '@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
//                ],
//            ],
//        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,//这里一定要改成false，true只是生成邮件在runtime文件夹下，不发邮件
            //多个发件源，再写一组 同名的 transport，但是发送时候指定下
            //指定使用'xxx@xxx.com'发送邮件 $mail->setFrom('xxx@xxxx.com');
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.exmail.qq.com',//邮箱服务器
                'username' => 'service@yiui.top',//用户名
                'password' => 'pcBGSmTrXe3B7yiG',//密码
                'port' => '465',//端口
                'encryption' => 'ssl',//加密
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['service@yiui.top'=>'LTS官方']
            ],
        ],


    ],
    'params' => $params,
];
