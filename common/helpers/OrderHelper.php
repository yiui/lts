<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/7/21
 * Time: 8:34
 */
namespace common\helpers;

use Yii;
use common\models\OrderConfig;
use yii\web\BadRequestHttpException;

class OrderHelper {
    /**
     * 返回18位 唯一订单号
     * 生成如：180228 1       01      44633 1234
     *       年月日  支付类型  业务类型  随机码 用户后4位校验码
     *       后9位：446331234 减去用户ID，除以10000，即可得到订单的时分秒（当天累计的秒数），也可以用于校验（若订单号使用了 time 参数同步的话）
     * @param string $st_name 业务表名
     * @param int $pt 支付类型
     * @param null|int $time 空为当前时间戳，否则应该填写订单的生成时间戳，用于精确同步订单的生成时间
     * @return string 订单号
     * @throws BadRequestHttpException
     */
    public static function Unid($st_name,$pt=1,$time=null){
        $st=array_search($st_name,OrderConfig::BUSINESS_TYPE);
        if ($st===false){
            throw new BadRequestHttpException('订单业务不存在！');
        }
        $ymd=date('ymd');//年月日
        if (empty($time)){
            $time=time();
        }
        $t=date('G',$time)*3600 + date('i',$time)*60 + date('s',$time);//当天当前秒数
        $t=$t*10000;//后面补4位，如此订单号后4位即是用户ID的校验码
        $uid=Yii::$app->user->id;//当前用户ID
        $tu=$t+$uid;//时间加上用户ID：某个时间内某个用户的订单
        $tu=Str::buquan($tu,9,0);//补全

        if ($st<10){
            $st='0'.$st;
        }
        if (empty($pt)){
            $pt=1;
        }

        //年月日。支付类型。业务类型。时间秒*10000和用户ID的和
        return $ymd.$pt.$st.$tu;
    }

    /**
     * 校验订单号是否正确，简单校验在不使用数据库查询订单的情况下
     * @param integer $order_id 订单号
     * @param integer | null $user_id 比对是否是某个用户的
     * @param integer | null $order_time 比对是否是某个用户某个时间的订单（提供此参数必须同时提供 $user_id）
     * @return bool 真假
     */
    public static function CkOrderId($order_id,$user_id=null,$order_time=null){
        $ymd=substr($order_id,0,6);//年月日
        $y=substr($order_id,0,2);//年月日
        if ($y<18 or $y > date('y')){
            return false;
        }
        $m=(int)substr($order_id,2,2);//年月日
        if ($m<1 or $m>12){
            return false;
        }
        $d=(int)substr($order_id,4,2);//年月日
        $time=strtotime('20'.$y.'-'.$m.'-'.$d.' 00:00:00');//订单当天零时的时间戳
        $md_num=date('t',$time);//当月有多少天
        if ($d<1 or $d>$md_num){
            return false;
        }
        if ($ymd>date('ymd')){
            return false;
        }

        $pt=substr($order_id,6,1);//支付类型
        if (!isset(OrderConfig::PAYMENT_TYPE[$pt])){
            return false;
        }

        $st=(int)substr($order_id,7,2);//业务类型
        if (!isset(OrderConfig::BUSINESS_TYPE[$st])){
            return false;
        }

        if ($user_id){
            if (substr($user_id,-4,4)!=(int)substr($order_id,-4,4)){
                return false;
            }

            if ($order_time){
                $ma=(int)substr($order_id,9,9);//后面随机码
                $dt=($ma-$user_id)/10000;
                if ($time+$dt != $order_time){
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * 根据订单号，找到订单
     * @param integer $order_id 订单号
     * @return null|object 空或目标订单对象
     */
    public static function getTarget($order_id){
        $type=(int)substr($order_id,7,2);

        if (!isset(OrderConfig::BUSINESS_TYPE[$type])){
            return null;
        }

        $tb_name=OrderConfig::BUSINESS_TYPE[$type];
        $class=Tb2Class::getClass($tb_name);
        if (!$class){
            return null;
        }
        if ($target=('common\\models\\'.$class)::findOne($order_id)){
            return $target;
        }else{
            return null;
        }
    }
}