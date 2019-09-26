<?php
namespace console\controllers;

use PhpOffice\PhpSpreadsheet\Shared\OLE\PPS\File;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class TestController extends Controller
{
    /**
     * @return int Exit code
     */
    public function actionIndex()
    {
        sleep(1);//延迟执行1秒

        echo "index operate is success\n";
        return ExitCode::OK;
    }

    /**
     * @return int Exit code
     */
    public function actionTest()
    {
        sleep(2);//延迟执行2秒
        echo "test action is success\n";
        return ExitCode::OK;
    }

}