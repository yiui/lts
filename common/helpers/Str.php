<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/6/2 Time: 11:28
 */

namespace common\helpers;

class Str {
    /**
     * 去除各种标签和转义特殊符号
     * @param string $str
     * @return string
     */
    public static function purify($str){
        if (empty($str)){
            return $str;
        }
        //去除前后空格
        $str = trim ($str);
        //strip_tags函数去除 HTML、XML 以及 PHP 的标签
        $str = strip_tags ($str);
        /*
         * 转换HTML特殊字符
        * 预定义的字符是：
        •& （和号） 成为 &amp;
        •" （双引号） 成为 &quot;
        •' （单引号） 成为 &#039;
        •< （小于） 成为 &lt;
        •> （大于） 成为 &gt;
        */
        $str = htmlspecialchars ($str);
        //使用反斜线引用字符串，注意系统是否已经使用转义了，默认使用
        /*
         * 在magic_quotes_gpc=On的情况下，如果输入的数据有
         * 单引号（’）、双引号（”）、反斜线（）与 NUL（NULL 字符）等字符都会被加上反斜线。
         * 这些转义是必须的，如果这个选项为off，那么我们就必须调用addslashes这个函数来为字符串增加转义。
         *
         * 当magic_quotes_gpc=On的时候，函数get_magic_quotes_gpc()就会返回1
         * 当magic_quotes_gpc=Off的时候，函数get_magic_quotes_gpc()就会返回0
         */
        if (!get_magic_quotes_gpc()){
            $str = addslashes ($str);
        }
        return $str;
    }

    /**
     * 去除所有代码标签
     * @param string $str
     * @return string
     */
    public static function notag($str){
        if (empty($str)){
            return $str;
        }
        //去除前后空格
        $str = trim ($str);
        //strip_tags函数去除 HTML、XML 以及 PHP 的标签
        $str = strip_tags ($str);
        //使用反斜线引用字符串，注意系统是否已经使用转义了，默认使用
        /*
         * 在magic_quotes_gpc=On的情况下，如果输入的数据有
         * 单引号（’）、双引号（”）、反斜线（）与 NUL（NULL 字符）等字符都会被加上反斜线。
         * 这些转义是必须的，如果这个选项为off，那么我们就必须调用addslashes这个函数来为字符串增加转义。
         *
         * 当magic_quotes_gpc=On的时候，函数get_magic_quotes_gpc()就会返回1
         * 当magic_quotes_gpc=Off的时候，函数get_magic_quotes_gpc()就会返回0
         */
        if (!get_magic_quotes_gpc()){
            $str = addslashes ($str);
        }
        return $str;
    }

    /**
     * 截取字符串的从头开始的相应长短返回
     * 特色：清除所有可能的标签
     * 请使用YII自带的StringHelper::truncate
     * 支持输出HTML的字符截断，只要将最后一个参数$asHtml设为true：
     * echo StringHelper::truncate('原内容', 9, '结尾后缀...', null, true);
     * @param string $content 需要截取的内容
     * @param int $listlen 截取的长度
     * @param string $suffix 尾部后缀
     * @return string 截取后的字符串
     */
    public static function cut($content,$listlen=128,$suffix='...'){
        if (empty($content)){
            return $content;
        }
        $str=self::notag($content);
        $strlen=mb_strlen($str,'utf-8');
        $newstr=mb_substr($str, 0,$listlen,'utf-8');
        return $newstr.($strlen>$listlen?$suffix:'');
    }

    /**
     * 统计真正的字符长度
     * @param string $str
     * @param string $type
     * @return int
     */
    public static function len($str,$type='utf-8'){
        if ($type=='utf-8'){
            //会将一个中文字符当作长度1
            return mb_strlen($str,'utf-8');
        }else if ($type=='all'){
            //按照ASCII码值
            $count = 0;

            for($i = 0; $i < strlen($str); $i++)
            {
                $value = ord($str[$i]);
                if($value > 127)
                {
                    if($value >= 192 && $value <= 223)
                        $i++;
                    elseif($value >= 224 && $value <= 239)
                        $i = $i + 2;
                    elseif($value >= 240 && $value <= 247)
                        $i = $i + 3;
                    else
                        die('Not a UTF-8 compatible string');
                }

                $count++;
            }

            return $count;
        }else{
            //对待一个UTF8的中文字符是3个长度,英文1个长度
            return strlen($str);
        }
    }

    /**
     * 去除中文的左边字符，解决 php ltrim 的乱码问题，适合中文字符串，英文字符串无需使用
     * @param string $str 原字符串
     * @param string $char 注意此处当成一个整体去处理的
     * @return string 处理好的字符串
     */
    public static function ltrim($str,$char){
        if (strpos($str,$char)!==false){
            $str = mb_substr($str,mb_strlen($char),mb_strlen($str)-mb_strlen($char));
        }
        return $str;
    }

    /**
     * 去除中文的右边字符，解决 php rtrim 的乱码问题，适合中文字符串，英文字符串无需使用
     * @param string $str 原字符串
     * @param string $char 注意此处当成一个整体去处理的
     * @return string 处理好的字符串
     */
    public static function rtrim($str,$char){
        if (strpos($str,$char)!==false){
            $str = mb_substr($str,0,mb_strlen($str)-mb_strlen($char));
        }
        return $str;
    }

    /**
     * 去除中文的两边字符，解决 php trim 的乱码问题，适合中文字符串，英文字符串无需使用
     * @param string $str 原字符串
     * @param string $char 注意此处当成一个整体去处理的
     * @return string 处理好的字符串
     */
    public static function trim($str,$char){
        if (strpos($str,$char)==0){
            $str = mb_substr($str,mb_strlen($char),mb_strlen($str)-mb_strlen($char));
        }
        if (strpos($str,$char) > 0){
            $str = mb_substr($str,0,mb_strlen($str)-mb_strlen($char));
        }

        return $str;
    }

    /**
     *
     * 实现  例article-cate 转成ArticleCate
     * @param string $controller_id 当前控制器ID
     * @return string 处理后的字符串
     */
    public static function ControllerName($controller_id){
        $con_name=str_replace(' ','',ucwords(str_replace('-',' ',$controller_id)));
        return $con_name;
    }
    /**
     * @param string $str 当前字符串
     * @param int $len 需要的总长度
     * @param bool $c false 不设定补全字符，采用随机数字， 否则只采用此字符
     * @return string 处理后的字符串
     */
    public static function buquan($str,$len,$c=false){
        if ($l=mb_strlen($str) >= $len){
            return $str;
        }
        $num=$len - $l;//需要补全的位数
        $pre=null;
        for ($i=0;$i<$num;$i++){
            if ($c!==false){
                $pre.=$c;
            }else{
                $pre.=rand(0,10);
            }
        }
        return $pre.$str;
    }

    /**
     * 数字转大写
     * @param int|string $num 数字或者字符串，字符串精确度更广
     * @return string
     */
    public static function num2DX($num){
        $num_array=['零','壹','贰','叁','肆','伍','陆','柒','捌','玖'];//数字数组
        $ws_array=['拾','佰','仟','万','拾','佰','仟','亿'];

        if (strpos($num,'.')!==false) {
            $nums = explode('.', $num, 2);//分割整数和小数
            $int = $nums[0];
            $dec = $nums[1];
            $z=null;
        }else{
            $int=$num;
            $z='整';
            $dec=null;
        }

        $len=strlen($int);
        $m_array=['圆'];
        $ws=0;
        $dw='';
        $wsn=count($ws_array);
        for ($i=0;$i < $len;$i++){
            if ($i>0){
                $dw=$ws_array[$ws];
                $ws++;
                if ($ws==$wsn){
                    $ws=0;
                }
            }
            $n=substr($int,$len-$i-1,1);//当前数字
            $m_array[]=$num_array[$n].$dw;
        }

        krsort($m_array);

        $str='';
        foreach ($m_array as $s){
            $str.=$s;
        }

        if ($dec!=null) {
            $dlen = strlen($dec);
            if ($dlen > 0) {
                for ($ii = 0; $ii < $dlen; $ii++) {
                    if ($ii == 0) {
                        $ddw = '角';
                    }elseif ($ii == 1) {
                        $ddw = '分';
                    }else{
                        $ddw='';
                    }

                    $dn = substr($dec, $ii, 1);//当前数字
                    $str .= $num_array[$dn] . $ddw;
                }
            }
        }

        return $str.$z;
    }
}