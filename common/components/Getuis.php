<?php

namespace common\components;

use davidxu\igt\Getui;

class Getuis extends Getui
{
    public $appId;

    public $appKey;

    public $masterSecret;

    public $logo;


//    public $appkey = 'Z6V3iF5sfj7pUSFzdS0Mf4';
//    public $appId = 'TdtdyXDoD19a4GivCOpTS4';
//    public $masterSecret = 'G9iibwKuYU7S8IAl5IhPY9';

    /**
     * Getui constructor.
     * @param string $appId
     * @param string $appkey
     * @param string $masterSecret
     */
    public function __construct()
    {
        $this->appId = 'TP2nAqAVRQ7CA7iwCbQZD1';
        $this->appkey = 'jENYldWIQy5NachBrZ0ZP8';
        $this->masterSecret = '1bXdeL3g347IgFEuqmQ4a4';
        $this->logo = 'http://img.jiarixiaodui.com/jrhb/image/15619511309488.jpg';
        parent::__construct($this->appId, $this->appkey, $this->masterSecret, $this->logo);
    }
}