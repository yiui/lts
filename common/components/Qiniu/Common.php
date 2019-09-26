<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/9/6
 * Time: 20:29
 */
namespace common\components\Qiniu;

use Qiniu\Auth;
use function Qiniu\base64_urlSafeEncode;

class Common {
    const AK='JZJKXndJcI6YLUzU2NEtoeRTislRk9gJa39WDnzC';
    const SK='AphYg7PmHh4mzjZ7zo8ZrM9Shbky9wyqmUeejwpk';

    const DOMAINS=[
        'open'=>'http://wzd-open.qicheshangwu.com',//开放空间
        'self'=>'http://wzd-in.qicheshangwu.com',//私有空间
    ];//空间地址
    const CALLBACK_URL='';//最好直接使用IP
    const PIPELINE='';//异步队列名字，空为公共队列，自有 qcsww 队列
    const BUCKET=[
        'open'=>'wzd-open',//开放空间
        'self'=>'wzd-in',//私有空间
    ];//空间名跟空间地址对应
    //const WATERIMG = 'http://wzd-open.qicheshangwu.com/common/img/logo.png';//水印图片URL
    const WATERIMG = 'http://wzd-open.qicheshangwu.com/common/img/watermark.png';//水印图片URL
    const EXPIRES=3600;//自定义凭证有效期

    public $auth;//生成的鉴权

    public function __construct(){
        // 初始化签权对象。
        $this->auth = new Auth(self::AK, self::SK);
    }

    /**
     * 私有文件下载或浏览URL
     * @param $baseUrl 文件URL
     * @param int $expires 下载过期时间
     * @return string
     */
    public function downloadUrl($baseUrl, $expires = 3600){
        // 私有空间中的外链 http://<domain>/<file_key>
        //$baseUrl = 'http://sslayer.qiniudn.com/1.jpg?imageView2/1/h/500';
        // 对链接进行签名
        $signedUrl = $this->auth->privateDownloadUrl($baseUrl, $expires);
        return $signedUrl;
    }

    //接收回调内容，并返回是否是正确的回调
    public function callback($callbackUrl=null,$json=true){
        //获取回调的body信息
        $callbackBody = file_get_contents('php://input');
//        $_body = file_get_contents('php://input');
//        $body = json_decode($_body, true);
//        $uid = $body['uid'];
//        $fname = $body['fname'];
//        $fkey = $body['fkey'];
//        $desc = $body['desc'];

        //回调的contentType
        $contentType = 'application/x-www-form-urlencoded';//JSON类型呢，考虑吗

        //回调的签名信息，可以验证该回调是否来自七牛
        $authorization = $_SERVER['HTTP_AUTHORIZATION'];

        //七牛回调的url，具体可以参考：http://developer.qiniu.com/docs/v6/api/reference/security/put-policy.html
        if (empty($callbackUrl)) {
            $url = self::CALLBACK_URL;
        }

        $isQiniuCallback = $this->auth->verifyCallback($contentType, $authorization, $url, $callbackBody);

        if ($isQiniuCallback) {
            if ($json) {
                $resp = array('ret' => 'success');
                return json_encode($resp);
            }else{
                return true;
            }
        } else {
            if ($json) {
                $resp = array('ret' => 'failed');
                return json_encode($resp);
            }else{
                return false;
            }
        }
    }

    /**
     * 另存为 指令，用管道符 | 拼接 saveas
     * 上传时是直接保存到目标空间的，使用这个指令可以将处理后的另存为
     * @param $key 另存为名称
     * @param null $bucket 另存到哪个空间
     * @return string
     */
    public static function saveas($key,$bucket=null){
        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }
        return 'saveas/'.base64_urlSafeEncode($bucket.':'.$key);
    }
}