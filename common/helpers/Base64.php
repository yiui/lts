<?php
/**
 * 图片base64编码和解码
 * User: www.yiui.top
 * Date: 2017/8/26
 * Time: 14:07
 */
namespace common\helpers;

use Yii;
use yii\helpers\FileHelper;

class Base64 {
    /**
     * 对提供的数据进行urlsafe的base64编码。
     * 注意这里不是普通的base64编码，需要使用本类的decode($str)解码
     *
     * @param string $data 待编码的数据，一般为字符串
     *
     * @return string 编码后的字符串
     */
    public static function encode($data)
    {
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($data));
    }

    /**
     * 对提供的urlsafe的base64编码的数据进行解码
     *
     * @param string $str 待解码的数据，一般为字符串
     *
     * @return string 解码后的字符串
     */
    public static function decode($str)
    {
        $find = array('-', '_');
        $replace = array('+', '/');
        return base64_decode(str_replace($find, $replace, $str));
    }

    /**
     * 读取并编码图片文件
     * @param $fileName     文件路径或别名
     * @return bool|string  base64字符串
     */
    public static function encodeImg($fileName,$returnBase=true){
        //是不是网络上的图片
        if (strpos($fileName,'http://')==0 or strpos($fileName,'https://')==0){
            if ($returnBase){
                return base64_encode(file_get_contents($fileName));
            }else {
                return 'data:' . FileHelper::getMimeType($fileName) . ';base64,' . base64_encode(file_get_contents($fileName));
            }
        //是不是本地图片
        }elseif (is_file(Yii::getAlias($fileName))){
            if ($returnBase){
                return base64_encode(file_get_contents($fileName));
            }else {
                return 'data:' . FileHelper::getMimeType($fileName) . ';base64,' . base64_encode(file_get_contents($fileName));
            }
        }
        return false;
    }

    /**
     * 保持base64成图片文件
     * @param $base64
     * @param $fileName
     * @return bool
     */
    public static function decodeImg($base64,$fileName){
        $type=explode(';',$base64,1);
        $type=explode('/',$type);
        $type=$type[1];

        //保存到文件
        if (strpos($fileName, 'http://') == 0 or strpos($fileName, 'https://') == 0) {
            if (file_put_contents($fileName . '.' . $type, base64_decode($base64))) {
                return true;
            }
        } elseif (file_put_contents(Yii::getAlias($fileName) . '.' . $type, base64_decode($base64))) {
            return true;
        }

        return false;
    }

}