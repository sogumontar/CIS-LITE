<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiNontahunan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permohonan-cuti-nontahunan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tgl_mulai')->textInput() ?>

    <?= $form->field($model, 'tgl_akhir')->textInput() ?>

    <?= $form->field($model, 'alasan_cuti')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lama_cuti')->textInput() ?>

    <?= $form->field($model, 'kategori_id')->textInput() ?>

    <?= $form->field($model, 'pengalihan_tugas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_oleh_hrd')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_oleh_atasan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_oleh_wr2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pegawai_id')->textInput() ?>

    <?= $form->field($model, 'deleted')->textInput() ?>

    <?= $form->field($model, 'deleted_at')->textInput() ?>

    <?= $form->field($model, 'deleted_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
