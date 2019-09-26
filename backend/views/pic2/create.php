<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Pic2 */

$this->title = Yii::t('backend', 'Create Pic2');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Pic2s'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic2-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
