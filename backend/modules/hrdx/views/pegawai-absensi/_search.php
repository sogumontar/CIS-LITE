<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\search\PegawaiAbsensiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pegawai-absensi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pegawai_absensi_id') ?>

    <?= $form->field($model, 'pegawai_id') ?>

    <?= $form->field($model, 'jenis_absen_id') ?>

    <?= $form->field($model, 'alasan') ?>

    <?= $form->field($model, 'pengalihan_tugas') ?>

    <?php // echo $form->field($model, 'jumlah_hari') ?>

    <?php // echo $form->field($model, 'approval_1') ?>

    <?php // echo $form->field($model, 'approval_2') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
