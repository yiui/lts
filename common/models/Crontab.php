<?php
namespace common\models;

use Yii;
use common\helpers\CronParser;

/**
 * 定时任务模型
 * @author jlb
 */
class Crontab extends \yii\db\ActiveRecord
{

    /**
     * switch字段的文字映射
     * @var array
     */
    private $switchTextMap = [
        0 => '关闭',
        1 => '开启',
    ];

    /**
     * status字段的文字映射
     * @var array
     */
    private $statusTextMap = [
        0 => '正常',
        1 => '任务保存',
    ];

    public static function getDb()
    {
        #注意!!!替换成自己的数据库配置组件名称
        return Yii::$app->db;
    }
    /**
     * 获取switch字段对应的文字
     * @author jlb
     * @return ''|string
     */
    public function getSwitchText()
    {
        if(!isset($this->switchTextMap[$this->switch])) {
            return '';
        }
        return $this->switchTextMap[$this->switch];
    }

    /**
     * 获取status字段对应的文字
     * @author jlb
     * @return ''|string
     */
    public function getStatusText()
    {
        if(!isset($this->statusTextMap[$this->status])) {
            return '';
        }
        return $this->statusTextMap[$this->status];
    }

    /**
     * 计算下次运行时间
     * @author jlb
     */
    public function getNextRunDate()
    {
        if (!CronParser::check($this->crontab_str)) {
            throw new \Exception("格式校验失败: {$this->crontab_str}", 1);
        }
        return CronParser::formatToDate($this->crontab_str, 1)[0];
    }

}