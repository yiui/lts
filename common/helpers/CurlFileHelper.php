<?php
/**
 * Created by yiui.top.
 * User: wen
 * Date: 2018/1/3
 * Time: 14:54
 */
namespace common\helpers;

class CurlFileHelper {
    public $ch;//curl 对象
    public $cfile;//CURLFile 对象

    function __construct($fileName,$mimeType=null,$postName=null)
    {
        $this->ch=new CurlHelper();
        //CURLFile::__construct ( string $filename [, string $mimetype [, string $postname ]] )
        $this->cfile=new \CURLFile($fileName,$mimeType,$postName);
    }

    function exec($fieldName){
        $data = array($fieldName => $this->cfile);
        curl_setopt($this->ch->ch, CURLOPT_POST,1);
        curl_setopt($this->ch->ch, CURLOPT_POSTFIELDS, $data);

        // Execute the handle
        curl_exec($this->ch->ch);
    }
}