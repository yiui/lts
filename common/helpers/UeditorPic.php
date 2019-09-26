<?php
/**
 * User: hy
 */

namespace common\helpers;

class UeditorPic
{


    /**
     * 提取百度编辑器上传，并进行路径替换,避免造成垃圾图片
     * @param string $content //编辑器提交的内容
     * @return string $content  替换路径后的内容和图片链接
     */

    //将外部图片批量保存至阿里云
    public static function getReomteImgToLOcal($content = '')
    {
        $content = str_replace('crossorigin="anonymous"', '', $content);
        $arr = array();
        //匹配图片的src
        preg_match_all('#<img.*? src="([^"]*)"[^>]*>#i', $content, $match);
        foreach ($match[1] as $imgurl) {
            $arr[] = $imgurl;
        }
        //匹配背景图片的url
        preg_match_all('#background-image:\s*url\(([^\)]*)\)#i', $content, $match);
        foreach ($match[1] as $imgurl) {
            $arr[] = $imgurl;
        }
        //匹配border-image图片
        preg_match_all('#-webkit-border-image:\s*url\(([^\)]*)\)#i', $content, $match);
        foreach ($match[1] as $imgurl) {
            $arr[] = $imgurl;
        }
        $arr = array_unique($arr);
        //die(var_dump($arr));
        $pics=array();
        foreach ($arr as $picurl) {
            $trueurl = str_replace('&quot;', '', $picurl);//去除双引号
            $trueurl = str_replace('//../../static', '', $trueurl);//去除双斜杠
            $content = str_replace($picurl, $trueurl, $content);
            $pics[] = substr($trueurl,26);
        }
        return ['pics'=>$pics,'content'=>$content];

    }


    /**
     * 提取百度编辑器上传，并进行路径替换
     * @param string $content //编辑器提交的内容
     * @param  string $tempDir 图片上传的本地位置
     * @param  string $ossObj 阿里云实例化对象
     * @param  string $basket 阿里云的存储空间
     * @param  string $osspath 阿里云存储位置   例$osspath='jrhb/party/'.date('Y/m/d').'/';
     * @param  string $domain oss域名 以/结尾
     * @return string $content  替换路径后的内容
     */

    //将外部图片批量保存至阿里云
    public static function getReomteImgToOss($content, $tempDir, $ossObj, $basket, $osspath, $domain = '')
    {
        $content = str_replace('crossorigin="anonymous"', '', $content);
        $arr = array();
        //匹配图片的src
        preg_match_all('#<img.*? src="([^"]*)"[^>]*>#i', $content, $match);
        foreach ($match[1] as $imgurl) {
            $arr[] = $imgurl;
        }
        //匹配背景图片的url
        preg_match_all('#background-image:\s*url\(([^\)]*)\)#i', $content, $match);
        foreach ($match[1] as $imgurl) {
            $arr[] = $imgurl;
        }
        //匹配border-image图片
        preg_match_all('#-webkit-border-image:\s*url\(([^\)]*)\)#i', $content, $match);
        foreach ($match[1] as $imgurl) {
            $arr[] = $imgurl;
        }
        $arr = array_unique($arr);
        //die(var_dump($arr));
        foreach ($arr as $picurl) {
            $picurl = str_replace('&quot;', '', $picurl);//去除双引号
            $trueUrl = $picurl;
            $picurlL = strtolower($picurl);
            //跳过res.wx.qq.com的图片，程序会卡死
            if (strpos($picurlL, 'jiarixiaodui.com') == false && strpos($picurlL, 'res.wx.qq.com') == false) {
                if (strpos($picurlL, 'gif')) {
                    $ext = 'gif';
                } elseif (strpos($picurlL, 'png')) {
                    $ext = 'png';
                } elseif (strpos($picurlL, 'bmp')) {
                    $ext = 'bmp';
                } elseif (strpos($picurlL, 'jpeg')) {
                    $ext = 'jpeg';
                } else {
                    $ext = 'jpg';
                }

                if (strpos($picurlL, 'qpic.cn')) {
                    $trueUrl = str_replace(substr($picurl, strpos($picurl, '?')), ".$ext", $picurl);//qpic去除盗链保护
                }


                $NewName = substr(str_shuffle('0123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ'), 0, 15);
                $NewName = $NewName . '.' . $ext;
                $TempName = $tempDir . $NewName;
                @$img = file_get_contents($trueUrl);
                if ($img) {
                    file_put_contents($TempName, $img);
                    $ossFile = $osspath . $NewName;
                    $ossObj->uploadFile($basket, $ossFile, $TempName);
                    $content = str_replace($picurl, $domain . $ossFile, $content);
                }
            }
        }
        return $content;

    }

}