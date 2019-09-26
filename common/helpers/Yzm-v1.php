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

class Yzm1 {
    public $name='yzm';//验证码保存名
    /**
     * @var int 允许验证多少次？
     */
    public $testLimit=3;
    /**
     * @var bool 是否只产生数字验证码
     */
    public $is_num=true;
    /**
     * @var int 验证码最小长度 4.
     */
    public $minLength=4;
    /**
     * @var int 验证码最大长度 6.
     */
    public $maxLength=6;

    /**
     * Yzm constructor.
     * @param null $name 验证码名字,应该填写，不然可能发生窜码，控制器_操作 名即可 如 site_login
     * @param bool $is_num 是否只产生数字验证码
     * @param int $testLimit 允许验证的最大次数，超过将重新生成
     * @param int $min 验证码最小长度
     * @param int $max 最大长度
     */
    function __construct($name=null)
    {
        $this->name=$name;
    }

    /**
     * 获取验证码，如果没有验证码生成一个返回
     * @param $regenerate 再次生成验证码
     * @return string 验证码值
     */
    public function getYzm($regenerate = false){
        $session = Yii::$app->getSession();
        $session->open();
        $name = $this->getSessionKey();
        if ($session[$name] === null || $regenerate) {
            $session[$name] = $this->generateVerifyCode();
            $session[$name . 'count'] = 1;
        }

        return $session[$name];
    }

    /**
     * 验证验证码
     * @param $name 验证码的名字，请保持获取时的名字
     * @param $input 输入的验证码
     * @param $caseSensitive 是否区分大小写
     * @return bool 真假
     */
    public function validYzm($input, $caseSensitive=false){
        $code = $this->getYzm();
        $valid = $caseSensitive ? ($input === $code) : strcasecmp($input, $code) === 0;
        $session = Yii::$app->getSession();
        $session->open();
        $vad_name = $this->getSessionKey() . 'count';
        $session[$vad_name]+=1;
        //验证次数过多，生成新的
        if ($valid || $session[$vad_name] > $this->testLimit && $this->testLimit > 0) {
            $this->getYzm(true);
        }
        return $valid;
    }

    /**
     * 生成验证码
     * @return string
     */
    protected function generateVerifyCode()
    {
        if ($this->minLength > $this->maxLength) {
            $this->maxLength = $this->minLength;
        }
        if ($this->minLength < 3) {
            $this->minLength = 3;
        }
        if ($this->maxLength > 10) {
            $this->maxLength = 10;
        }
        $length = mt_rand($this->minLength, $this->maxLength);

        $code = '';
        if ($this->is_num){
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
    protected function getSessionKey(){
        return $this->name?'__yzm/'.$this->name:'__yzm/VerifyCode';
    }
}