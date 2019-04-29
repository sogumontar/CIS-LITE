<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\search\RiwayatPendidikanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="riwayat-pendidikan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'riwayat_pendidikan_id') ?>

    <?= $form->field($model, 'jenjang_id') ?>

    <?= $form->field($model, 'universitas') ?>

    <?= $form->field($model, 'jurusan') ?>

    <?= $form->field($model, 'thn_mulai') ?>

    <?php // echo $form->field($model, 'thn_selesai') ?>

    <?php // echo $form->field($model, 'ipk') ?>

    <?php // echo $form->field($model, 'gelar') ?>

    <?php // echo $form->field($model, 'judul_ta') ?>

    <?php // echo $form->field($model, 'pegawai_id') ?>

    <?php // echo $form->field($model, 'website') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'profile_id') ?>

    <?php // echo $form->field($model, 'id_old') ?>

    <?php // echo $form->field($model, 'jenjang') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
