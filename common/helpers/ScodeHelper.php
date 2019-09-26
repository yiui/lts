<?php
/**
 * 安全码生成和解码助手
 * Created by www.yiui.top.
 * User: Zhao Wenming
 * Date: 2017/11/30
 * Time: 14:08
 */
namespace common\helpers;

use yii;
class ScodeHelper {
    const PWD='www.yiui.top';

    /**
     * Created by www.yiui.top.
     * User: Zhao Wenming
     * @param array $data 明文组成的数组数据
     * @return string 加密后的密文
     */
    public static function encode(array $data){
        return Base64::encode(Yii::$app->security->encryptByKey(json_encode($data),self::PWD));
    }

    /**
     * Created by www.yiui.top.
     * User: Zhao Wenming
     * @param $data 加密后的密文
     * @return mixed 解密后的对象内容，使用 -> 访问里面内容
     */
    public static function decode($data){
        return json_decode(Yii::$app->security->decryptByKey(Base64::decode($data),self::PWD));//解码
    }

}