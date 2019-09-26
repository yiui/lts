<?php
/**

 * Date: 2018/2/1
 * Time: 10:07
 */
namespace common\models;

/**
 * 系统基本配置
 * 为防止之前数据混乱，添加新配置的元素应该在数组后部添加
 * Class Config
 * @package common\models
 */
class Config {
    /**
     * CDN
     */
    const CDN='http://cdn.wzd.com/static/';
    /**
     * STATIC PATH
     */
    const STATIC_DIR_PATH=__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'static'.DIRECTORY_SEPARATOR;

    /**
     * 产品刷新过期时间
     */
    const UPDATE_OVER_TIME=2592000;//30天

    /**
     * 热点城市，品牌，地区使用的分类数组
     */
    const HOT_ITEM_ARRAY=[
        'all'=>'默认',
        'car-new'=>'新车',
        'car-new-bs'=>'新车分期',
        'car-old'=>'二手车',
        'car-old-bs'=>'二手车分期',
        'car-bs'=>'汽车分期',
        'car-gold'=>'汽车金融',
        'res-car'=>'汽车资源',
        'res-gold'=>'金融资源'
    ];

    /**
     * 产品控制器及其名称数组
     */
    const ACTION_NAME=[
        'car-new'=>'新车',
        'car-new-bs'=>'新车分期',
        'car-old'=>'二手车',
        'car-old-bs'=>'二手车分期',
        'car-bs'=>'汽车分期',
        'car-gold'=>'汽车金融',
        'res-car'=>'汽车资源',
        'res-gold'=>'金融资源',
    ];

    /**
     * 常用快递数组
     */
    const KUAI_DI = [
        '顺丰快递',
        '圆通速递',
        '韵达快递',
        '中通快递',
        '申通快递',
        '百世汇通',
        '宅急送',
        'EMS邮政特快',
        '全峰快递',
        '中国邮政'
    ];
}