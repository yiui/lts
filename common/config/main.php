<?php
return [
    'language'=>'zh-CN',//配置语言
    'timeZone'=>'PRC',//默认时区
    'version'=>'0.5',//该属性指定应用的版本,默认为'1.0'， 其他代码不使用的话可以不配置。
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'Aliyunoss' => [
            'class' => 'common\components\Aliyunoss',
        ],
        //国际化语言翻译组件
        'i18n'=>[
            'translations' => [
                //当类别为common时启用
                'common' => [
                    'class' => 'yii\i18n\PhpMessageSource',//使用这个类处理，因为我们保存在了php文件中，也可以使用其他方法
                    'basePath' => '@common/messages',//语言字典存放目录
                    'fileMap' => [
                        'common' => 'common.php',//文件映射
                        //---如果找不到找第二个。。。
                        //最后找不到使用源语言
                    ],
                ],
                //可以使用*，当类别为power* 即power前缀

                'backend' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',//语言字典存放目录
                    'fileMap' => [
                        'backend' => 'backend.php',
                    ],
                ],
            ],

        ],

    ],
];
