<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/9/28
 * Time: 16:39
 */
namespace common\helpers;

class Tb2Class {
    /**
     * 将表名转换为类名
     * @param string $target_tb 表名
     * @return bool|string 类名，不含名称空间，请使用时加上名称空间
     */
    static function getClass($target_tb){
        //转换表名为类名
        if (strpos($target_tb,'_')){
            $classs=explode('_',$target_tb);
            $class='';
            foreach ($classs as $word){
                $class.=ucfirst($word);
            }
        }else{
            $class=ucfirst($target_tb);
        }
        if (class_exists('common\\models\\'.$class)){
            return $class;
        }else{
            return false;
        }
    }

    /**
     * 将控制器名转换为表名
     * @param string $action 控制器名称
     * @return mixed 表名
     */
    static function getAction2Tb($action){
        if (strpos($action,'-')){
            return str_replace('-','_',$action);
        }
        return $action;
    }

    /**
     * 将表名转换为控制器名，操作方法名请在后端加
     * @param string $target_tb 表名
     * @return mixed 控制器名称
     */
    static function getAction($target_tb){
        if (strpos($target_tb,'_')){
            return str_replace('_','-',$target_tb);
        }
        return $target_tb;
    }

    /**
     * 通过类名换成表名
     * @param string $class 类名
     * @param bool|int $haveModel 是否存在公共模型，存在直接返回
     * @return string 表名
     */
    static function getTb($class,$haveModel=1){
        //如果是目标表的模型直接获取
        if ($haveModel and class_exists('common\\models\\'.$class)){
            return ('common\\models\\'.$class)::getTableSchema()->name;
        }

        $tbs=preg_split("/(?=[A-Z])/", $class);
        $tbname='';
        foreach ($tbs as $tb){
            $tbname.=$tb.'_';
        }
        $tbname=rtrim($tbname,'_');
        return $tbname;
    }
}