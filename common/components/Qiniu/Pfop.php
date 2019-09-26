<?php
/**
 * 对空间中已经存在的内容进行异步持久化操作
 * 对于已经保存到七牛空间的文件，可以通过发送持久化的数据处理指令来进行处理
 * User: ThinkPad
 * Date: 2017/9/6
 * Time: 20:59
 */
namespace common\components\Qiniu;

use function Qiniu\base64_urlSafeEncode;
use Qiniu\Processing\PersistentFop;

class Pfop extends Common {
    public $pfop;

    /**
     * pfop constructor.
     * @param null $config Qiniu\Config; 配置对象
     */
    public function __construct($config = null){
        parent::__construct();
        $this->pfop = new PersistentFop($this->auth, $config);
    }

    /**
     * 压缩打包一组文件
     * @param array $keys 一组文件
     * @param $zipKey 目标压缩包名
     * @param null $bucket 空间名
     * @param null $notify_url 回调URL
     * @param bool $force 是否强制执行一次新的指令
     */
    public function zip(array $keys,$zipKey,$bucket=null,$notify_url = null,$pipeline=null,$force = false){
        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }

        if (empty($pipeline)){
            $pipeline=self::PIPELINE;
        }

        if (empty($notifyUrl)){
            $notifyUrl=self::CALLBACK_URL;
        }

        $domain=self::DOMAINS[array_column(self::BUCKET,$bucket)[0]];//获取此空间对应的域名

        //压缩后的key
        $zipKey = $zipKey.'.zip';

        $fops = 'mkzip/2';
        foreach ($keys as $key) {                        // 进行zip压缩的url
            $fops .= '/url/' . \Qiniu\base64_urlSafeEncode($domain.$key);
        }
        $fops .= '|saveas/' . \Qiniu\base64_urlSafeEncode($bucket.':'.$zipKey);

        /**
         * 对资源文件进行异步持久化处理
         * @param $bucket     资源所在空间
         * @param $key        待处理的源文件
         * @param $fops       string|array  待处理的pfop操作，多个pfop操作以array的形式传入。
         *                    eg. avthumb/mp3/ab/192k, vframe/jpg/offset/7/w/480/h/360
         * @param $pipeline   资源处理队列
         * @param $notify_url 处理结果通知地址
         * @param $force      是否强制执行一次新的指令
         *
         *
         * @return array 返回持久化处理的persistentId, 和返回的错误。
         *
         * @link http://developer.qiniu.com/docs/v6/api/reference/fop/
         */
        list($id, $err) = $this->pfop->execute($bucket, $keys[0], $fops, $pipeline, $notify_url, $force);

        echo "\n====> pfop mkzip result: \n";
        if ($err != null) {
            var_dump($err);
        } else {
            echo "PersistentFop Id: $id\n";

            $res = "http://api.qiniu.com/status/get/prefop?id=$id";
            echo "Processing result: $res";
        }
    }

    /**
     * 视频截图
     * 请配置：
     * $config = new \Qiniu\Config();
     * $config->useHTTPS = true;
     * $pfop = new PersistentFop($auth, $config);
     * @param $key 要转码的文件所在的空间和文件名。
     * @param int $w 转码后的宽
     * @param int $h 转码后的高
     * @param null $savekey 转码后的名
     * @param null $bucket 空间名
     * @param null $notifyUrl 转码完成后通知到你的业务服务器
     * @param null $pipeline 转码是使用的队列名称。
     * @param bool $force
     */
    public function vframe($key,$w=480,$h=360,$savekey=null,$bucket=null,$notifyUrl=null,$pipeline=null,$force=false){
        if (empty($savekey)){
            $savekey='vframe_'.$w.'_'.$h.$key;
        }

        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }

        if (empty($pipeline)){
            $pipeline=self::PIPELINE;
        }

        if (empty($notifyUrl)){
            $notifyUrl=self::CALLBACK_URL;
        }

        //要进行视频截图操作
        $fops = 'vframe/jpg/offset/1/w/'.$w.'/h/'.$h.'/rotate/90|saveas/' .
            \Qiniu\base64_urlSafeEncode($bucket . ':'.$savekey);

        list($id, $err) = $this->pfop->execute($bucket, $key, $fops, $pipeline, $notifyUrl, $force);
        echo "\n====> pfop avthumb result: \n";
        if ($err != null) {
            var_dump($err);
        } else {
            echo "PersistentFop Id: $id\n";
        }
    }

    /**
     * 视频转码
     * @param $key 源视频
     * @param int $w 转码后的宽
     * @param int $h 高
     * @param null $savekey 转码后的名
     * @param null $bucket 空间
     * @param null $notifyUrl 回调
     * @param null $pipeline
     * @param bool $force
     */
    public function zm($key,$w=640,$h=360,$savekey=null,$bucket=null,$notifyUrl=null,$pipeline=null,$force=false){
        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }

        if (empty($pipeline)){
            $pipeline=self::PIPELINE;
        }

        if (empty($notifyUrl)){
            $notifyUrl=self::CALLBACK_URL;
        }
        if (empty($savekey)){
            $savekey='zm_'.$w.'_'.$h.'_'.$savekey;
        }

        //要进行转码的转码操作。 http://developer.qiniu.com/docs/v6/api/reference/fop/av/avthumb.html
        $fops = 'avthumb/mp4/s/'.$w.'x'.$h.'/vb/1.4m|saveas/' . \Qiniu\base64_urlSafeEncode($bucket . ':'.$savekey);

        list($id, $err) = $this->pfop->execute($bucket, $key, $fops, $pipeline, $notifyUrl, $force);
        echo "\n====> pfop avthumb result: \n";
        if ($err != null) {
            var_dump($err);
        } else {
            echo "PersistentFop Id: $id\n";
        }
    }

    /**
     * 视频转码并打上水印
     * @param $key 源视频
     * @param int $w 转码后的宽
     * @param int $h 高
     * @param null $savekey 转码后的名
     * @param null $bucket 空间
     * @param null $notifyUrl 回调
     * @param null $pipeline
     * @param bool $force
     */
    public function watermark($key,$w=640,$h=360,$savekey=null,$waterimg=null,$bucket=null,$notifyUrl=null,$pipeline=null,$force=false){
        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }

        if (empty($pipeline)){
            $pipeline=self::PIPELINE;
        }

        if (empty($notifyUrl)){
            $notifyUrl=self::CALLBACK_URL;
        }
        if (empty($savekey)){
            $savekey='zm_wm_'.$w.'_'.$h.'_'.$savekey;
        }
        if (empty($waterimg)){
            $waterimg=$this->waterimg;
        }


        //需要添加水印的图片UrlSafeBase64
        //可以参考http://developer.qiniu.com/code/v6/api/dora-api/av/video-watermark.html
        $base64URL = base64_urlSafeEncode($waterimg);

        //水印参数
        $fops = 'avthumb/mp4/s/'.$w.'x'.$h.'/vb/1.4m/image/' . $base64URL . '|saveas/'
            . \Qiniu\base64_urlSafeEncode($bucket . ':'.$savekey);

        list($id, $err) = $this->pfop->execute($bucket, $key, $fops, $pipeline, $notifyUrl, $force);
        echo "\n====> pfop avthumb result: \n";
        if ($err != null) {
            var_dump($err);
        } else {
            echo "PersistentFop Id: $id\n";
        }
    }

    /**
     * 查询该 触发持久化处理的状态
     * @param $persistentId 触发持久化处理后返回的 Id
     */
    public function getStatus($persistentId){
        // 通过persistentId查询该 触发持久化处理的状态
        $status = PersistentFop::status($persistentId);

        var_dump($status);
    }
}