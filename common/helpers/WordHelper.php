<?php
/**
 * Created by PhpStorm.
 * User: ThinkPad
 * Date: 2017/7/21
 * Time: 8:34
 */
namespace common\helpers;

use common\models\Area;
use common\models\Shops;
use Yii;
use yii\web\Cookie;

class WordHelper {

    /**
     * 将 html 转成 word 格式
     * @param $html
     * @return string
     */
    public static function html2Word($html){
        $header='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40"> <head> <meta http-equiv=Content-Type content="text/html; charset=utf-8"> </head> <body>';
        $html=$header.$html.'</body></html>';

        return $html;
    }

    /**
     * 将 html 转成 excel 格式
     * @param $html
     * @return string
     */
    public static function html2Excel($html){
        $header='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"> <head> <meta http-equiv=Content-Type content="text/html; charset=utf-8"> </head> <body>';
        $html=$header.$html.'</body></html>';

        return $html;
    }

    /**
     * 直接下载word文档
     * @param $html 不含html head body 部分
     * @param null $fileName
     */
    public static function downWord($html,$fileName=null){
        $fileContent = self::html2Word($html);
        if (empty($fileName)){
            $fileName=time();
        }
        $fileName = iconv("utf-8", "GBK", $fileName);

        header("Content-Type: application/doc");
        header('Content-Transfer-Encoding: binary');
        header("Content-Disposition: attachment; filename=" . $fileName . ".doc");

        echo $fileContent;
    }

    /**
     * 直接下载 excel 文档
     * @param $html 不含html head body 部分，应为表格代码
     * @param null $fileName
     */
    public static function downExcel($html,$fileName=null){
        $fileContent = self::html2Excel($html);
        if (empty($fileName)){
            $fileName=time();
        }
        $fileName = iconv("utf-8", "GBK", $fileName);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Length: '.filesize('./test.xls'));
        header('Content-Transfer-Encoding: binary');
        header('Content-Disposition: attachment; filename=' . $fileName . '.xlsx');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        echo $fileContent;
    }
}