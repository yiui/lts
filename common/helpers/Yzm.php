<?php
/**
 * 生成验证码并保存到session中
 * 支持验证
 * User: wkk
 * Date: 2017/10/8
 * Time: 14:43
 */
namespace common\helpers;
use yii;

class Yzm {
    const NAME='yzm';//验证码保存名
    /**
     * @var int 允许验证多少次？
     */
    const TEST_NUM=4;

    /**
     * @var int 验证码最小长度 4.
     */
    const MIN_LEN=4;
    /**
     * @var int 验证码最大长度 6.
     */
    const MAX_LEN=6;

    /**
     * 获取验证码，如果没有验证码生成一个返回
     * @param string $name 验证码名称
     * @param bool $regenerate 是否再次生成验证码
     * @return string 验证码值
     */
    public static function getYzm($name=null,$regenerate = false){
        $session = Yii::$app->getSession();
        $session->open();
        $name = self::getSessionKey($name);
        if ($session[$name] === null || $regenerate) {
            $session[$name] = self::generateVerifyCode();
            $session[$name . 'count'] = 1;
        }

        return $session[$name];
    }

    /**
     * 验证验证码
     * @param string $input 输入的验证码
     * @param string $name 验证码的名字，请保持获取时的名字
     * @param bool $caseSensitive 是否区分大小写
     * @return bool 真假
     */
    public static function validYzm($input,$name=null, $caseSensitive=false){
        $code = self::getYzm($name);
        echo $input.'=='.$code;
        $valid = $caseSensitive ? ($input === $code) : strcasecmp($input, $code) === 0;
        $session = Yii::$app->getSession();
        $session->open();
        $vad_name = self::getSessionKey($name) . 'count';
        $session[$vad_name]+=1;
        //验证次数过多，生成新的【这里验证失败并不重新生成，如果需要加上 $valid || 】
        if ($session[$vad_name] > self::TEST_NUM) {
            self::getYzm($name,true);
        }
        return $valid;
    }

    /**
     * 生成验证码
     * @param bool $is_num  是不是数字
     * @return string
     */
    protected static function generateVerifyCode($is_num=true)
    {
        $length = mt_rand(self::MIN_LEN, self::MAX_LEN);

        $code = '';
        if ($is_num){
            $letters = '012345';
            $vowels = '6789';
            for ($i = 0; $i < $length; ++$i) {
                if ($i % 2 && mt_rand(0, 10) > 2 || !($i % 2) && mt_rand(0, 10) > 9) {
                    $code .= $vowels[mt_rand(0, 3)];
                } else {
                    $code .= $letters[mt_rand(0, 5)];
                }
            }
        }else{
            $letters = 'bcdfghjklmnpqrstvwxyz0123456789';
            $vowels = 'aeiou';
            for ($i = 0; $i < $length; ++$i) {
                if ($i % 2 && mt_rand(0, 10) > 2 || !($i % 2) && mt_rand(0, 10) > 9) {
                    $code .= $vowels[mt_rand(0, 4)];
                } else {
                    $code .= $letters[mt_rand(0, 30)];
                }
            }
        }

        return $code;
    }

    /**
     * 生成验证码的 Session Key
     * @param $name
     * @return string
     */
    protected static function getSessionKey($name){
        return $name?'__yzm/'.$name:'__yzm/'.self::NAME;
    }
}