<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Pic */

$this->title = Yii::t('backend', 'Create Pic');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Pics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
