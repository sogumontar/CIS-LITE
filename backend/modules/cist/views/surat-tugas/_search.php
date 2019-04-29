<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\search\SuratTugasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="surat-tugas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'surat_tugas_id') ?>

    <?= $form->field($model, 'perequest') ?>

    <?= $form->field($model, 'no_surat') ?>

    <?= $form->field($model, 'tempat') ?>

    <?= $form->field($model, 'tanggal_berangkat') ?>

    <?php // echo $form->field($model, 'tanggal_kembali') ?>

    <?php // echo $form->field($model, 'agenda') ?>

    <?php // echo $form->field($model, 'review_surat') ?>

    <?php // echo $form->field($model, 'desc_surat_tugas') ?>

    <?php // echo $form->field($model, 'pengalihan_tugas') ?>

    <?php // echo $form->field($model, 'atasan') ?>

    <?php // echo $form->field($model, 'jenis_surat_id') ?>

    <?php // echo $form->field($model, 'surat_tugas_file_id') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
