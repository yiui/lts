<?php
namespace common\helpers;

class Itime {

    //今天00点的时间戳
    public static function toDay(){
       return strtotime(date("Y-m-d"));
    }

    //明天00点的时间戳
    public static function yesterday(){
        return strtotime(date("Y-m-d",strtotime("+1 day")));
}


    /**这个月的第一天或者说上个月的最后一天
     * @return false|int
     */
    public static function getMonthFirstDay(){
        return  strtotime(date("Y-m-01",time()));
    }

    /**这个月的最后一天
     * @return false|int
     */
    public static function getMonthLastDay(){
        $now_month=strtotime(date('Y-m-01',strtotime("+1 month")))-1;
        return  $now_month;//上一个月的时间-1 s 表示 当前月的最后一分最后1s
     }

    /**上个月的第一天
     * @return false|int
     */
     public static function getLastMonthFirstDay(){
         $last_month=strtotime(date('Y-m-01',strtotime("-1 month")));
         return $last_month;
     }

}