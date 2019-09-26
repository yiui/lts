<?php

use kartik\file\FileInput;

$this->registerJsFile('https://cdn.jsdelivr.net/bluebird/latest/bluebird.min.js');

?>
<div class="car-new-in-pic-index">
    <?php
    echo FileInput::widget([
        'model' => $model,//此模型在文件无法使用ajax上传时去接收文件，保证文件的上传成功
        'attribute' => 'file[]',
        'options' => ['multiple' => true],
        'pluginOptions'=>$model::initOptions($initPics,$params),
        'pluginEvents'=>$model::initEvents()
    ]);
    ?>
</div>
