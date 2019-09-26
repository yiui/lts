<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/9/6
 * Time: 20:33
 */
namespace common\components\Qiniu;

use Qiniu\Storage\BucketManager;

class Bucket extends Common {
    public $bucketMgr;

    public function __construct(){
        parent::__construct();
        //初始化BucketManager
        $this->bucketMgr = new BucketManager($this->auth());
    }

    /**
     * 获取文件的信息
     * @param $key 文件名
     * @param $bucket 空间
     */
    public function getStatus($key,$bucket=null){
        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }
        //获取文件的状态信息
        list($ret, $err) = $this->bucketMgr->stat($bucket, $key);
        echo "\n====> $key stat : \n";
        if ($err !== null) {
            var_dump($err);
        } else {
            var_dump($ret);
        }
    }

    /**
     * 抓取某个URL的文件保存到空间里
     * @param $url 文件URL
     * @param $key 保存的名字，无则使用 hash 名
     * @param null $bucket 空间
     */
    public function getTo($url, $key=null,$bucket=null){
        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }
        // 指定抓取的文件保存名称
        list($ret, $err) = $this->bucketMgr->fetch($url, $bucket, $key);
        echo "=====> fetch $url to bucket: $bucket  key: $key\n";
        if ($err !== null) {
            var_dump($err);
        } else {
            print_r($ret);
        }
    }

    /**
     * 复制一个文件
     * @param $srcKey   源文件
     * @param $destKey  目标文件
     * @param null $srcBucket   源空间
     * @param null $destBucket  目标空间
     */
    public function copy($srcKey,$destKey,$srcBucket=null,$destBucket=null,$force=true){
        if (empty($srcBucket)){
            $srcBucket=self::BUCKET['open'];
        }

        if (empty($destBucket)){
            $destBucket=$srcBucket;
        }
        $err = $this->bucketMgr->copy($srcBucket, $srcKey, $destBucket, $destKey, $force);
        if ($err) {
            print_r($err);
        }
    }

    /**
     * 将一组文件复制到目的地
     * @param $srcBucket 源空间
     * @param $old_files[] 源文件数组，由文件key组成的一维数组
     * @param $destBucket 目标空间，也可以同名
     * @param $new_files[] 新文件名数组，与$old_files[]对应
     */
    public function copyAll($old_files, $new_files=null,$srcBucket=null, $destBucket=null,$force=true){
        //每次最多不能超过1000个
        $files = array();
        if (empty($new_files)) {
            foreach ($old_files as $key) {
                $files[$key] = 'copy_'.$key;
            }
        }else{
            foreach ($old_files as $key) {
                $files[$key] = $new_files[$key];
            }
        }

        if (empty($srcBucket)){
            $srcBucket=self::BUCKET['open'];
        }

        if (empty($destBucket)){
            $destBucket=$srcBucket;
        }

        $ops = $this->bucketMgr->buildBatchCopy($srcBucket, $files, $destBucket, $force);
        list($ret, $err) = $this->bucketMgr->batch($ops);
        if ($err !== null) {
            var_dump($err);
        } else {
            echo "Success!";
        }
    }

    /**
     * 将一个文件移动到目的地
     * @param $srcBucket 源空间
     * @param $old_file 源文件
     * @param $destBucket 目标空间，也可以同名
     * @param $new_file 新文件名
     */
    public function move($old_file, $new_file=null,$srcBucket=null, $destBucket=null,$force=true){
        if (empty($srcBucket)){
            $srcBucket=self::BUCKET['open'];
        }

        if (empty($destBucket)){
            $destBucket=$srcBucket;
        }

        if (empty($new_file)){
            $new_file='move_'.$old_file;
        }

        $err = $this->bucketMgr->move($srcBucket, $old_file, $destBucket, $new_file, $force);
        if ($err) {
            print_r($err);
        }
    }

    /**
     * 将一组文件复制到目的地
     * @param $srcBucket 源空间
     * @param $old_files[] 源文件数组，由文件key组成的一维数组
     * @param $destBucket 目标空间，也可以同名
     * @param $new_files[] 新文件名数组，与$old_files[]对应
     */
    public function moveAll($old_files, $new_files=null,$srcBucket=null, $destBucket=null,$force=true){
        //每次最多不能超过1000个
        $files = array();
        if (empty($new_files)) {
            foreach ($old_files as $key) {
                $files[$key] = 'move_'.$key;
            }
        }else{
            foreach ($old_files as $key) {
                $files[$key] = $new_files[$key];
            }
        }

        if (empty($srcBucket)){
            $srcBucket=self::BUCKET['open'];
        }

        if (empty($destBucket)){
            $destBucket=$srcBucket;
        }

        $ops = $this->bucketMgr->buildBatchMove($srcBucket, $files, $destBucket, $force);
        list($ret, $err) = $this->bucketMgr->batch($ops);
        if ($err) {
            print_r($err);
        } else {
            print_r($ret);
        }
    }

    /**
     * 删除一个文件
     * @param $key 文件
     * @param null $bucket 空间
     */
    public function delete($key,$bucket=null){
        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }
        $err = $this->bucketMgr->delete($bucket, $key);
        if ($err) {
            print_r($err);
        }
    }

    /**
     * 删除一组文件
     * @param $keys
     * @param $bucket
     */
    public function deleteAll($keys,$bucket=null){
        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }
        $ops = $this->bucketMgr->buildBatchDelete($bucket, $keys);
        list($ret, $err) = $this->bucketMgr->batch($ops);
        if ($err !== null) {
            var_dump($err);
        } else {
            echo "Success!";
        }
    }

    /**
     * 延时删除一个文件，即设置文件有效期
     * @param $key 文件
     * @param $days 天数
     * @param null $bucket 空间
     */
    public function timeDelete($key, $days,$bucket=null){
        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }
        $err = $this->bucketMgr->deleteAfterDays($bucket, $key, $days);
        if ($err) {
            print_r($err);
        }
    }

    /**
     * 延时删除一组文件，即设置文件有效期
     * @param $files[] 文件数组
     * @param int $time 天数
     * @param null $bucket
     */
    public function timeDeleteAll($files,$time=0,$bucket=null){
        $keyDayPairs = array();
        //day=0表示永久存储
        foreach ($files as $key) {
            $keyDayPairs[$key] = $time;
        }
        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }

        $ops = $this->bucketMgr->buildBatchDeleteAfterDays($bucket, $keyDayPairs);
        list($ret, $err) = $this->bucketMgr->batch($ops);
        if ($err) {
            print_r($err);
        } else {
            print_r($ret);
        }
    }

    // 要列取的空间名称     要列取文件的公共前缀      上次列举返回的位置标记，作为本次列举的起点信息。    本次列举的条目数
    public function listDir($bucket=null,$prefix = '',$marker = '',$limit = 3,$delimiter = '/'){
        if (empty($bucket)){
            $bucket=self::BUCKET['open'];
        }
        // 要列取文件的公共前缀
        $prefix = '';

        // 上次列举返回的位置标记，作为本次列举的起点信息。
        $marker = '';

        // 本次列举的条目数
        $limit = 200;

        $delimiter = '/';

        // 列举文件
        do {
            list($ret, $err) = $this->bucketMgr->listFiles($bucket, $prefix, $marker, $limit, $delimiter);
            if ($err !== null) {
                echo "\n====> list file err: \n";
                var_dump($err);
            } else {
                $marker = null;
                if (array_key_exists('marker', $ret)) {
                    $marker = $ret['marker'];
                }
                echo "Marker: $marker\n";
                echo "\nList Items====>\n";
                //var_dump($ret['items']);
                print('items count:' . count($ret['items']) . "\n");
                if (array_key_exists('commonPrefixes', $ret)) {
                    print_r($ret['commonPrefixes']);
                }
            }
        } while (!empty($marker));
    }

}