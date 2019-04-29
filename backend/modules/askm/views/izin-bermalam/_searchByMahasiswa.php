<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinBermalamSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="izin-bermalam-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'izin_bermalam_id') ?>

    <?= $form->field($model, 'rencana_berangkat') ?>

    <?= $form->field($model, 'rencana_kembali') ?>

    <?= $form->field($model, 'realisasi_berangkat') ?>

    <?= $form->field($model, 'realisasi_kembali') ?>

    <?php // echo $form->field($model, 'desc') ?>

    <?php // echo $form->field($model, 'tujuan') ?>

    <?php // echo $form->field($model, 'dim_id') ?>

    <?php // echo $form->field($model, 'keasramaan_id') ?>

    <?php // echo $form->field($model, 'status_request_id') ?>

    <?php // echo $form->field($model, 'izin_laptop_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
