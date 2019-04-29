<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\search\ProgramSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="program-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'program_id') ?>

    <?= $form->field($model, 'struktur_jabatan_has_mata_anggaran_id') ?>

    <?= $form->field($model, 'kode_program') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'tujuan') ?>

    <?php // echo $form->field($model, 'sasaran') ?>

    <?php // echo $form->field($model, 'target') ?>

    <?php // echo $form->field($model, 'desc') ?>

    <?php // echo $form->field($model, 'rencana_strategis_id') ?>

    <?php // echo $form->field($model, 'volume') ?>

    <?php // echo $form->field($model, 'satuan_id') ?>

    <?php // echo $form->field($model, 'harga_satuan') ?>

    <?php // echo $form->field($model, 'jumlah_sebelum_revisi') ?>

    <?php // echo $form->field($model, 'jumlah') ?>

    <?php // echo $form->field($model, 'status_program_id') ?>

    <?php // echo $form->field($model, 'diusulkan_oleh') ?>

    <?php // echo $form->field($model, 'tanggal_diusulkan') ?>

    <?php // echo $form->field($model, 'dilaksanakan_oleh') ?>

    <?php // echo $form->field($model, 'disetujui_oleh') ?>

    <?php // echo $form->field($model, 'tanggal_disetujui') ?>

    <?php // echo $form->field($model, 'ditolak_oleh') ?>

    <?php // echo $form->field($model, 'tanggal_ditolak') ?>

    <?php // echo $form->field($model, 'is_revisi') ?>

    <?php // echo $form->field($model, 'direvisi_oleh') ?>

    <?php // echo $form->field($model, 'tanggal_direvisi') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
