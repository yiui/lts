<?php
/**
 * Copyright © 2017 www.yiui.top All Rights Reserved
 * User: yiui Date: 2017/11/11 Time: 13:07
 */
namespace common\actions;

use common\models\CarBrand;
use yii\base\Action;

class CarBrandAction extends Action {
    /**
     * 区域联动下拉菜单
     */
    public function run($id){
        if (empty($id) or !is_numeric($id)){
            echo '<option>-</option>';
        }else {
            if ($branches = CarBrand::all($id)) {
                foreach ($branches as $id => $name) {
                    echo '<option value="' . $id . '">' . $name . '</option>';
                }
            } else {
                echo '<option>-</option>';
            }
        }
    }

}