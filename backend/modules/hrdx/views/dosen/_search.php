<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\mref\models\search\DosenSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dosen-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'dosen_id') ?>

    <?= $form->field($model, 'prodi_id') ?>

    <?= $form->field($model, 'golongan_kepangkatan_id') ?>

    <?= $form->field($model, 'jabatan_akademik_id') ?>

    <?= $form->field($model, 'status_ikatan_kerja_dosen_id') ?>

    <?php // echo $form->field($model, 'gbk_id') ?>

    <?php // echo $form->field($model, 'aktif_start') ?>

    <?php // echo $form->field($model, 'aktif_end') ?>

    <?php // echo $form->field($model, 'role_dosen_id') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'pegawai_id') ?>

    <?php // echo $form->field($model, 'nidn') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
