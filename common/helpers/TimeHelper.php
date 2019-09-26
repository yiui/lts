<?php
/**
 * 时间助手
 * User: admin
 * Date: 2017/10/25
 * Time: 10:38
 */
namespace common\helpers;

class TimeHelper {

    /**
     * 返回时间发生在什么时候前
     * @param int $time 时间戳，应该是过去时间
     * @return string 什么时段前或格式化的时间
     */
    public static function ago($time){
        $diff=time()-$time;
        if ($diff<=60){
            return '刚刚';
        }elseif ($diff>60 and $diff<=3600){
            return floor($diff/60).'分钟前';
        }elseif ($diff>3600 and $diff<=86400){
            return floor($diff/3600).'小时前';
        }elseif ($diff>86400 and $diff<=604800){
            return floor($diff/86400).'天前';
        }else{
            return date('Y-m-d H:i:s', $time);
        }
    }

    /**
     * 返回几天后
     * @param int $time 时间戳，应该是未来时间
     * @return string 什么时段前或格式化的时间
     */
    public static function later($time){
        $diff=$time-time();
        if ($diff<0){
            return date('Y-m-d H:i:s', $time);
        }elseif ($diff<=60){
            return '将要';
        }elseif ($diff>60 and $diff<=3600){
            return floor($diff/60).'分钟后';
        }elseif ($diff>3600 and $diff<=86400){
            return floor($diff/3600).'小时后';
        }elseif ($diff>86400 and $diff<=604800){
            return floor($diff/86400).'天后';
        }else{
            return date('Y-m-d H:i:s', $time);
        }
    }

    /**
     * 计算秒数对应的年龄、年份
     */
    public static function getYearNum($time){
        return floor((time()-$time)/24*60*60*365);
    }

    /**
     * 返回上几个月的几号零点的时间戳，默认上一个月的1号
     *
     * @param int $lm 上几个月，默认上一个月
     * @param int $d 上几个月的几号？默认上个月1号
     * @return false|int
     */
    public static function lastM($lm=1,$d=1){
        if ($lm>12) {
            $lm = 12;
        }
        if (($m=(date('n')-$lm))==0) {
            $Y = date('Y') - 1;
        }else{
            $Y = date('Y');
        }

        $ymd=self::goodYMD($m,$d,$Y);
        //返回
        return mktime(0,0,0,$ymd['m'],$ymd['d'],$ymd['Y']);
    }

    /**
     * 某年某月的某天的23:59:59 或 00:00:00 时间戳，默认今年这月的最后一天
     * lastD(5) 今年5月最后一天的23：59：59
     * lastD(5,2) 今年5月2号的23：59：59
     * lastD(5,2,2000) 2000年5月2号的23：59：59
     * lastD(5,2,2000,true) 2000年5月2号的00:00:00
     * @param int $m 默认是这个月
     * @param int $d 默认这个月最后一天,如果不知道最后一天是几号，就写上31
     * @param int $y 默认今年
     * @param bool $is24 默认返回23:59:59，假返回00:00:00
     * @return false|int
     */
    public static function getYmd($m=null,$d=31,$y=null,$is24=true){
        $ymd=self::goodYMD($m,$d,$y);
        //返回
        if ($is24) {
            return mktime(23, 59, 59, $ymd['m'], $ymd['d'], $ymd['Y']);
        }else{
            return mktime(0, 0, 0, $ymd['m'], $ymd['d'], $ymd['Y']);
        }
    }

    /**
     * 得到正确的年月日
     * @param null $m 某月
     * @param int $d 某日
     * @param null $y 某年
     * @return array 年月日
     */
    private static function goodYMD($m=null,$d=31,$y=null){
        //确定年份
        if (empty($y)) {
            $Y = date('Y',time());
        }else{
            $Y = date('Y',mktime(0,0,0,1,1,$y));
        }

        //确定月份
        if (empty($m)){
            $m=(int)date('m',time());
        }else{
            if ($m<1){
                $m=1;
            }
            if ($m>12){
                $m=12;
            }
        }

        //确定日期
        if ($d<1){
            $d=1;
        }
        $max_d=date('t',mktime(0,0,0,$m,1,$Y));//指定的月份有几天
        if ($d>$max_d){
            $d=$max_d;
        }

        return [
          'Y'=>$Y, 'm'=>$m,'d'=>$d
        ];
    }

    /**
     * 两个时间相差几年几个月
     */
    public static function diffYearAndMonth($time1,$time2){
        if(!is_numeric($time1) || !is_numeric($time2) || $time1<$time2){
            return false;
        }
        list($tmp_time1['y'],$tmp_time1['m'])=explode("-",date('Y-m',$time1));
        list($tmp_time2['y'],$tmp_time2['m'])=explode("-",date('Y-m',$time2));
        $all=abs($tmp_time1['y']-$tmp_time2['y'])*12 +$tmp_time1['m']-$tmp_time2['m'];
        $y=floor($all/12) ? floor($all/12).'年' : '';
        $m=($all % 12) ? ($all % 12).'个月' : '';
        return (!$y && !$m ) ? '新店' : $y.$m;
    }

    public static function diffYear($time1,$time2){
        if(!is_numeric($time1) || !is_numeric($time2) || $time1<$time2){
            return false;
        }
        list($tmp_time1['y'],$tmp_time1['m'])=explode("-",date('Y-m',$time1));
        list($tmp_time2['y'],$tmp_time2['m'])=explode("-",date('Y-m',$time2));
        $all=abs($tmp_time1['y']-$tmp_time2['y'])*12 +$tmp_time1['m']-$tmp_time2['m'];
        $y=ceil($all/12) ? ceil($all/12) : '0';
        return $y;
    }
}