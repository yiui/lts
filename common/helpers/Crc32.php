<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/13
 * Time: 14:14
 */
namespace common\helpers;

class Crc32 {
    /**
     * 计算文件的crc32检验码:
     *
     * @param $file string  待计算校验码的文件路径
     *
     * @return string 文件内容的crc32校验码
     */
    public static function file($file)
    {
        $hash = hash_file('crc32b', $file);
        $array = unpack('N', pack('H*', $hash));
        return sprintf('%u', $array[1]);
    }

    /**
     * 计算输入流的crc32检验码
     *
     * @param $data 待计算校验码的字符串
     *
     * @return string 输入字符串的crc32校验码
     */
    public static function data($data)
    {
        $hash = hash('crc32b', $data);
        $array = unpack('N', pack('H*', $hash));
        return sprintf('%u', $array[1]);
    }
}