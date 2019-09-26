<?php

namespace common\components;

use yiui\fadada\FddServer;

/**
 *
 * 法大大接口继承,
 *
 **/
class Fadada extends FddServer
{
    public $appId;
    public $appSecret;
    public $host;

    public function __construct()
    {
        $this->appId = "402186";
        $this->appSecret = "DvKA8ETEW7wgGU09AtzXkRsS";
        $this->host = 'http://test.api.fabigbig.com:8888/api/';
        //$this->host = 'https://testapi.fadada.com:8443/api/';
        parent::__construct($this->appId, $this->appSecret, $this->host);
    }


    /**
     * 业务内容需要定制化修改
     * 可在下面进行函数重写
     **/

    public function hello(){
        return 'hello world';
    }
}
