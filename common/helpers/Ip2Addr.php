<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/7/1
 * Time: 8:03
 * 各种获取IP地址对应的省市区地址方法
 */
namespace common\helpers;

class Ip2Addr {
    /**
     * 使用17monipdb.dat库区查找
     * @param string $ip IP地址
     * @return mixed|string
     */
    public static function ip17mon($ip){
        /**
         * array[
         *      0=>国家
         *      1=>省
         *      2=>市
         *      3=>区？一般为空
         * ]
         */
        return Ip17Mon::find($ip);
    }

    /**
     * 使用17monipdb.datx库区查找
     * @param string $ip IP地址
     * @return mixed|string
     */
    public static function ip17monX($ip){
        /**
         * array[
         *      0=>国家
         *      1=>省
         *      2=>市
         *      3=>区？一般为空
         * ]
         */
        return Ip17MoniX::find($ip);
    }

    /**
     * http://www.cz88.net/ IP数据库（纯真版）下载地址
     * 使用QQ地址库
     * @param string $ip IP地址
     * @return mixed
     */
    public static function ipqqwry($ip){
        /**
         * array[
         *      [beginip]=>IP开始段
         *      [endip]=>IP结束段
         *      [country]=>省市区
         *      [area]=>运营商或具体地址
         * ]
         */
        $ipadd=new IpQqwry();
        return $ipadd->ip2addr($ip);
    }

    /**
     * 在百度地图IP库，在线查找
     * @param $ip
     * @return bool|string
     */
    public static function baidu($ip){
        $key = 'F454f8a5efe5e577997931cc01de3974';
        $url = 'http://api.map.baidu.com/location/ip?ak=' . $key . '&ip=' . long2ip((int)$ip);
        return @file_get_contents($url);
        $content = json_decode(@file_get_contents($url), true);
        if (empty($content)) {
            return '';
        }
        if (isset($content['status']) && empty($content['status'])) {
            return @$content['content']['address_detail']['province']
                . "\t" . @$content['content']['address_detail']['city']
                . "\t" . @$content['content']['address_detail']['district']
                . "\t" . @$content['content']['address_detail']['street']
                . "\t" . @$content['content']['address_detail']['street_number'];
        }
        return '';
    }

    /**
     * 在淘宝IP库里在线查找
     * @param $ip
     * @return bool|string
     *
     * 为了保障服务正常运行，每个用户的访问频率需小于10qps。
     *
     * 1. 请求接口（GET）：
    /service/getIpInfo.php?ip=[ip地址字串]
    2. 响应信息：
    （json格式的）国家 、省（自治区或直辖市）、市（县）、运营商
    3. 返回数据格式：
    {"code":0,"data":{"ip":"210.75.225.254","country":"\u4e2d\u56fd","area":"\u534e\u5317",
    "region":"\u5317\u4eac\u5e02","city":"\u5317\u4eac\u5e02","county":"","isp":"\u7535\u4fe1",
    "country_id":"86","area_id":"100000","region_id":"110000","city_id":"110000",
    "county_id":"-1","isp_id":"100017"}}
    其中code的值的含义为，0：成功，1：失败。
     */
    public static function taobao($ip){
        $url = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . long2ip((int)$ip);
        $content = json_decode(@file_get_contents($url), true);
        return @file_get_contents($url);
        if (empty($content)) {
            return '';
        }
        if (isset($content['code']) && $content['code']==0) {
            return @$content['data']['country']
                . "\t" . @$content['data']['area']
                . "\t" . @$content['data']['region']
                . "\t" . @$content['data']['city']
                . "\t" . @$content['data']['county']
                . "\t" . @$content['data']['isp'];
        }
        return '';
    }

    /**
     * 在新浪IP库里在线查找
     * @param $ip
     * @return bool|string
     */
    public static function sina($ip){
        $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=' . long2ip((int)$ip);
        $content = json_decode(@file_get_contents($url), true);
        return @file_get_contents($url);
        if (empty($content)) {
            return '';
        }
        if (isset($content['ret']) && !empty($content['ret'])) {
            return @$content['country']
                . "\t" . @$content['province']
                . "\t" . @$content['city']
                . "\t" . @$content['district']
                . "\t" . @$content['isp']
                . "\t" . @$content['type']
                . "\t" . @$content['desc'];
        }
        return '';
    }
}