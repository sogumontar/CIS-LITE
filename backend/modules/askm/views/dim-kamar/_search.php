<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\search\DimKamar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dim-kamar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_dim_kamar') ?>

    <?= $form->field($model, 'status_dim_kamar') ?>

    <?= $form->field($model, 'dim_id') ?>

    <?= $form->field($model, 'kamar_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
