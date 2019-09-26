<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Pic2 */

$this->title = Yii::t('backend', 'Update {modelClass}: ', [
    'modelClass' => 'Pic2',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Pic2s'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="pic2-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
