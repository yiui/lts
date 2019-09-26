<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/10/11
 * Time: 11:47
 */
namespace common\components\Qiniu;

use Qiniu\Cdn\CdnManager;

class Cdn extends Common {
    public $cdnMgr;//cdn管理对象
    //时间戳防盗链密钥，后台获取
    const ENCRYPT_KEY='';

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * 刷新文件
     * @param $urls 源文件数组
     */
    public function refreshUrls($urls){
        list($refreshResult, $refreshErr) = $this->cdnMgr->refreshUrls($urls);
        if ($refreshErr != null) {
            var_dump($refreshErr);
        } else {
            echo "refresh request sent\n";
            print_r($refreshResult);
        }
    }

    /**
     * 刷新目录
     * 如： ['http://phpsdk.qiniudn.com/test/','http://phpsdk.qiniudn.com/test2/']
     * @param $dirs 目录数组
     */
    public function refreshDirs($dirs){
        list($refreshResult, $refreshErr) = $this->cdnMgr->refreshDirs($dirs);
        if ($refreshErr != null) {
            var_dump($refreshErr);
        } else {
            echo "refresh request sent\n";
            print_r($refreshResult);
        }
    }

    /**
     * 同时刷新一组文件和目录
     * @param $urls 源文件数组
     * @param $dirs 目录数组
     */
    public function refreshUrlsAndDirs($urls, $dirs){
        // 目前客户默认没有目录刷新权限，刷新会有400038报错，参考：https://developer.qiniu.com/fusion/api/1229/cache-refresh
        // 需要刷新目录请工单联系技术支持 https://support.qiniu.com/tickets/category
        list($refreshResult, $refreshErr) = $this->cdnMgr->refreshUrlsAndDirs($urls, $dirs);
        if ($refreshErr != null) {
            var_dump($refreshErr);
        } else {
            echo "refresh request sent\n";
            print_r($refreshResult);
        }
    }

    /**
     * 获取时间戳防盗链URL
     * @param $url 源文件URL
     * @param int $time
     */
    public function getDurationUrl($url,$time=3600){
        //时间戳防盗链密钥，后台获取
        $signedUrl = CdnManager::createTimestampAntiLeechUrl($url, self::ENCRYPT_KEY, $time);
        print($signedUrl);
    }

    /**
     * 获取日志
     * @param $domains 空间的域名一维数组
     * @param null $logDate 日志具体日期
     */
    public function getLog($domains,$logDate=null){
        if (empty($logDate)){
            $logDate=date('Y-m-d');
        }

        //获取日志下载链接
        //参考文档：http://developer.qiniu.com/article/fusion/api/log.html

        list($logListData, $getLogErr) = $this->cdnMgr->getCdnLogList($domains, $logDate);
        if ($getLogErr != null) {
            var_dump($getLogErr);
        } else {
            echo "get cdn log list success\n";
            print_r($logListData);
        }
    }
}