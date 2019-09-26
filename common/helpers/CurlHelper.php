<?php
/**
 * Created by yiui.top.
 * User: wen
 * Date: 2018/1/3
 * Time: 14:54
 */
namespace common\helpers;

class CurlHelper {

    public $ch;//curl 资源

    function __construct($url=null)
    {
        //$url CURLOPT_URL 选项将会被设置成这个值。你也可以使用curl_setopt()函数手动地设置这个值。
        $this->ch=curl_init($url);
        return $this;
    }

    /**
     * 设置相应选项的值
     * @see http://us3.php.net/manual/zh/function.curl-setopt.php 参考php官方文档设置
     * @param $option
     * @param $value
     */
    function set($option,$value){
        curl_setopt($this->ch,$option,$value);
    }

    /**
     * 批量设置选项
     * @param $options 选项和值数组
     */
    function setAll($options){
        curl_setopt_array($this->ch, $options);
    }

    /**
     * 执行请求
     * @return mixed
     */
    function exec(){
        return curl_exec($this->ch);//执行并返回结果
    }

    /**
     * 返回最后一个错误信息
     * @return array
     */
    function getError(){
        return [
          'code'=>curl_errno($this->ch),//错误号
          'msg'=>curl_error($this->ch),//字符串
        ];
    }

    /**
     * 获取一个cURL连接资源句柄的信息
     * @param $opt 某个具体参数的信息，如果 opt 被设置，以字符串形式返回它的值。否则，返回返回一个关联数组
     * @return mixed
     */
    function getInfo($opt){
        /**
         * 这个参数可能是以下常量之一:
        ◦ CURLINFO_EFFECTIVE_URL - 最后一个有效的URL地址
        ◦ CURLINFO_HTTP_CODE - 最后一个收到的HTTP代码
        ◦ CURLINFO_FILETIME - 远程获取文档的时间，如果无法获取，则返回值为“-1”
        ◦ CURLINFO_TOTAL_TIME - 最后一次传输所消耗的时间
        ◦ CURLINFO_NAMELOOKUP_TIME - 名称解析所消耗的时间
        ◦ CURLINFO_CONNECT_TIME - 建立连接所消耗的时间
        ◦ CURLINFO_PRETRANSFER_TIME - 从建立连接到准备传输所使用的时间
        ◦ CURLINFO_STARTTRANSFER_TIME - 从建立连接到传输开始所使用的时间
        ◦ CURLINFO_REDIRECT_TIME - 在事务传输开始前重定向所使用的时间
        ◦ CURLINFO_SIZE_UPLOAD - 以字节为单位返回上传数据量的总值
        ◦ CURLINFO_SIZE_DOWNLOAD - 以字节为单位返回下载数据量的总值
        ◦ CURLINFO_SPEED_DOWNLOAD - 平均下载速度
        ◦ CURLINFO_SPEED_UPLOAD - 平均上传速度
        ◦ CURLINFO_HEADER_SIZE - header部分的大小
        ◦ CURLINFO_HEADER_OUT - 发送请求的字符串
        ◦ CURLINFO_REQUEST_SIZE - 在HTTP请求中有问题的请求的大小
        ◦ CURLINFO_SSL_VERIFYRESULT - 通过设置CURLOPT_SSL_VERIFYPEER返回的SSL证书验证请求的结果
        ◦ CURLINFO_CONTENT_LENGTH_DOWNLOAD - 从Content-Length: field中读取的下载内容长度
        ◦ CURLINFO_CONTENT_LENGTH_UPLOAD - 上传内容大小的说明
        ◦ CURLINFO_CONTENT_TYPE - 下载内容的Content-Type:值，NULL表示服务器没有发送有效的Content-Type: header
         */
        return curl_getinfo($this->ch,$opt);
    }

    /**
     * 暂停和恢复连接
     * @param $bitmask
     * @return int 返回一个错误代码 (如果没有错误则返回CURLE_OK常量)。
     */
    function pause($bitmask){
        return curl_pause($this->ch, $bitmask);
    }

    /**
     * 将给定的 cURL 句柄所有选项重新设置为默认值。
     */
    function reset(){
        curl_reset($this->ch);
    }

    function __destruct()
    {
        curl_close($this->ch);//关闭cURL资源，并且释放系统资源
    }

    /***********************************************************************************************/
    /************* 自定义函数助手                                                  ********************/
    /***********************************************************************************************/

    /**
     * PHP CURL 访问的如果是 https 协议，可能需要添加以下语句：
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false)
     */

    /**
     * GET 请求
     * @param $url 目标地址，请把参数等一起带上
     * @param int $timeout 超时时间
     * @return bool|mixed
     */
    public static function get($url,$timeout=5){
        if(empty($url) || $timeout <= 0){
            return false;
        }

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        return curl_exec($ch);
    }

    /**
     * POST 请求
     * @param $url 目标地址
     * @param $data 请求参数数组
     * @param int $timeout 超时时间
     * @return bool|mixed
     */
    public static function post($url,$data,$timeout=5){
        if(empty($url) || $timeout <=0){
            return false;
        }

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_bulid_query($data));
        curl_setopt($ch, CURLOPT_POST,true);//标识这个请求是一个POST请求。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_TIMEOUT,$timeout);

        return curl_exec($ch);
    }
}