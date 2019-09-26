<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
        'v2' => [
            'class' => 'api\modules\v2\Module',
        ],
    ],
    "aliases" => [    //别名配置
        '@v1' => '@api/modules/v1',
        '@v2' => '@api/modules/v2',
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
        ],
//        'response' => [//自定义返回格式
//            'class' => 'yii\web\Response',
//            'on beforeSend' => function ($event) {
//                $response = $event->sender;
//                $response->data = [
//                    'success' => $response->isSuccessful,
//                    'code' => $response->getStatusCode(),
//                    'message' => $response->statusText,
//                    'data' => $response->data,
//                ];
//                $response->statusCode = 200;
//            },
//        ],
        'user' => [
            'identityClass' => 'api\models\Adminuser',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'loginUrl' => null,
            //'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
         'session' => [
             // this is the name of the session cookie used for login on the backend
             'name' => 'advanced-api',
         ],
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [//匹配规则注意:严格的匹配放在前面，避免错误匹配
                ['class' => 'yii\rest\UrlRule',
                   // 'pluralize'=>false,//取消复数
                    'controller' => 'article',
                    'ruleConfig'=>[
                        'class'=>'yii\web\UrlRule',
                        'defaults'=>[
                            'expand'=>'createdBy',
                        ]
                    ],
                    'extraPatterns'=>[
                        'POST search' => 'search'
                    ],
                ],
                ['class' => 'yii\rest\UrlRule',
                     'pluralize'=>false,//取消复数
                    'controller' => 'v1/article',
                ],
                ['class' => 'yii\rest\UrlRule',
                    'controller' => 'v2/member',
                   // 'pluralize'=>true,//取消复数
                ],
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                'v1/<controller:\w+>/<action:\w+>'=>'v1/<controller>/<action>',
                'v2/<controller:\w+>/<action:\w+>'=>'v2/<controller>/<action>',

            ],
        ],

    ],
    'params' => $params,
];
