<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use common\models\Area;
use yii\base\Action;

class AreaAction extends Action {
    /**
     * 区域联动下拉菜单
     */
    public function run($id){
        if (empty($id) or !is_numeric($id)){
            echo "<option>-</option>";
        }else{
            if ($branches = Area::all($id)){
                foreach ($branches as $k => $name) {
                    echo '<option value="' . $k . '">' . $name . '</option>';
                }
            } else {
                echo "<option>-</option>";
            }
        }
    }

}