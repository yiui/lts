<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Task */

$this->title = Yii::t('backend', 'Create Task');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
