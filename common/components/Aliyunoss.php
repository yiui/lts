<?php

namespace common\components;

use Yii;
use yii\base\Component;
use OSS\OssClient;

class Aliyunoss extends Component
{
    public static $oss;

    public function __construct()
    {
        parent::__construct();
        $accessKeyId = Yii::$app->params['oss']['accessKeyId'];                 //获取阿里云oss的accessKeyId
        $accessKeySecret = Yii::$app->params['oss']['accessKeySecret'];         //获取阿里云oss的accessKeySecret
        $endpoint = Yii::$app->params['oss']['endPoint'];                       //获取阿里云oss的endPoint
        self::$oss = new OssClient($accessKeyId, $accessKeySecret, $endpoint);  //实例化OssClient对象
    }

    /**
     * 使用阿里云oss上传文件
     * @param $object   保存到阿里云oss的文件名
     * @param $filepath 文件在本地的绝对路径
     * @return bool     上传是否成功
     */
    public function upload($object, $filepath)
    {
        $res = false;
        $bucket = Yii::$app->params['oss']['bucket'];               //获取阿里云oss的bucket
        if (self::$oss->uploadFile($bucket, $object, $filepath)) {  //调用uploadFile方法把服务器文件上传到阿里云oss
            $res = true;
        }

        return $res;
    }


    /**
     * 删除指定文件
     *
     * @param string $bucket bucket名称
     * @param string $filename objcet名称例如 ltes/img/a.jpg
     * @param string $content 上传的内容
     * @return null
     */
    public function delete($object)
    {
        $bucket = Yii::$app->params['oss']['bucket'];    //获取阿里云oss的bucket
        self::$oss->deleteObject($bucket, $object); //调用deleteObject方法把服务器文件上传到阿里云oss
        return null;
    }

    public function test()
    {
        echo 123;
        echo "success";
    }

    /**
     * 上传内存中的内容
     *
     * @param string $bucket bucket名称
     * @param string $filename objcet名称例如 ltes/img/a.jpg
     * @param string $content 上传的内容
     * @return null
     */
    public function upBase64($filename, $content)
    {
        $bucket = $bucket = Yii::$app->params['oss']['bucket'];
        self::$oss->putObject($bucket, $filename, $content);
        return null;
    }


}

?>