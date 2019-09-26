<?php
namespace common\helpers;

/**
 * crontab格式解析工具类
 * @author jlb <497012571@qq.com>
 */
class CronParser
{

    protected static $weekMap = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    /**
     * 检查crontab格式是否支持
     * @param  string $cronstr
     * @return boolean true|false
     */
    public static function check($cronstr)
    {
        $cronstr = trim($cronstr);

        if (count(preg_split('#\s+#', $cronstr)) !== 5) {
            return false;
        }

        $reg = '#^(\*(/\d+)?|\d+([,\d\-]+)?)\s+(\*(/\d+)?|\d+([,\d\-]+)?)\s+(\*(/\d+)?|\d+([,\d\-]+)?)\s+(\*(/\d+)?|\d+([,\d\-]+)?)\s+(\*(/\d+)?|\d+([,\d\-]+)?)$#';
        if (!preg_match($reg, $cronstr)) {
            return false;
        }

        return true;
    }

    /**
     * 格式化crontab格式字符串
     * @param  string $cronstr
     * @param  interge $maxSize 设置返回符合条件的时间数量, 默认为1
     * @return array 返回符合格式的时间
     */
    public static function formatToDate($cronstr, $maxSize = 1)
    {

        if (!static::check($cronstr)) {
            throw new \Exception("格式错误: $cronstr", 1);
        }

        $tags = preg_split('#\s+#', $cronstr);

        $crons = [
            'minutes' => static::parseTag($tags[0], 0, 59), //分钟
            'hours'   => static::parseTag($tags[1], 0, 23), //小时
            'day'     => static::parseTag($tags[2], 1, 31), //一个月中的第几天
            'month'   => static::parseTag($tags[3], 1, 12), //月份
            'week'    => static::parseTag($tags[4], 0, 6), // 星期
        ];

        $crons['week'] = array_map(function($item){
            return static::$weekMap[$item];
        }, $crons['week']);

        $nowtime = strtotime(date('Y-m-d H:i'));
        $today = getdate();
        $dates = [];
        foreach ($crons['month'] as $month) {
            // 获取单月最大天数
            $maxDay = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));
            foreach ($crons['day'] as $day) {
                if ($day > $maxDay) {
                    break;
                }
                foreach ($crons['hours'] as $hours) {
                    foreach ($crons['minutes'] as $minutes) {
                        $i = mktime($hours, $minutes, 0, $month, $day);
                        if ($nowtime > $i) {
                            continue;
                        }
                        $date = getdate($i);

                        // 解析是第几天
                        if ($tags[2] != '*' && in_array($date['mday'], $crons['day'])) {
                            $dates[] = date('Y-m-d H:i', $i);
                        }

                        // 解析星期几
                        if ($tags[4] != '*' && in_array($date['weekday'], $crons['week'])) {
                            $dates[] = date('Y-m-d H:i', $i);
                        }

                        // 天与星期几
                        if ($tags[2] == '*' && $tags[4] == '*') {
                            $dates[] = date('Y-m-d H:i', $i);
                        }


                        if (isset($dates) && count($dates) == $maxSize) {
                            break 4;
                        }
                    }
                }
            }
        }

        return array_unique($dates);
    }
    /**
     * 解析元素
     * @param  string $tag  元素标签
     * @param  integer $tmin 最小值
     * @param  integer $tmax 最大值
     * @throws \Exception
     */
    protected static function parseTag($tag, $tmin, $tmax)
    {
        if ($tag == '*') {
            return range($tmin, $tmax);
        }

        $step = 1;
        $dateList = [];

        if (false !== strpos($tag, '/')) {
            $tmp = explode('/', $tag);
            $step = isset($tmp[1]) ? $tmp[1] : 1;

            $dateList = range($tmin, $tmax, $step);
        }
        else if (false !== strpos($tag, '-')) {
            list($min, $max) = explode('-', $tag);
            if ($min > $max) {
                list($min, $max) = [$max, $min];
            }
            $dateList = range($min, $max, $step);
        }
        else if (false !== strpos($tag, ',')) {
            $dateList = explode(',', $tag);
        }
        else {
            $dateList = array($tag);
        }

        // 越界判断
        foreach ($dateList as $num) {
            if ($num < $tmin || $num > $tmax) {
                throw new \Exception('数值越界');
            }
        }

        sort($dateList);

        return $dateList;
    }
}