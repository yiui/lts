<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Link */

$this->title = Yii::t('backend', 'Create Link');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
