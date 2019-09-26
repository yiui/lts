<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Alioss */

$this->title = Yii::t('backend', 'Create Alioss');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Aliosses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alioss-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
