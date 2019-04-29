<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiTahunan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permohonan-cuti-tahunan-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php 
        echo $kuota; 
    ?>
    <?= $form->field($model, 'waktu_pelaksanaan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alasan_cuti')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lama_cuti')->textInput() ?>

    <?= $form->field($model, 'pengalihan_tugas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_oleh_hrd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_oleh_atasan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_oleh_wr2')->textInput(['maxlength' => true]) ?>

    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
