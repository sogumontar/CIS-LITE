<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\Pembinaan */

$this->title = 'Edit Bentuk Pembinaan';
$this->params['breadcrumbs'][] = ['label' => 'Bentuk Pembinaan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Current page', 'url' => ['view', 'id' => $model->pembinaan_id]];
$this->params['breadcrumbs'][] = 'Edit';
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pembinaan-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
