<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\search\IzinKeluarSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="izin-keluar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'izin_keluar_id') ?>

    <?= $form->field($model, 'rencana_berangkat') ?>

    <?= $form->field($model, 'rencana_kembali') ?>

    <?= $form->field($model, 'realisasi_berangkat') ?>

    <?= $form->field($model, 'realisasi_kembali') ?>

    <?php // echo $form->field($model, 'desc') ?>

    <?php // echo $form->field($model, 'dim_id') ?>

    <?php // echo $form->field($model, 'dosen_id') ?>

    <?php // echo $form->field($model, 'staf_id') ?>

    <?php // echo $form->field($model, 'keasramaan_id') ?>

    <?php // echo $form->field($model, 'status_request_baak') ?>

    <?php // echo $form->field($model, 'status_request_keasramaan') ?>

    <?php // echo $form->field($model, 'status_request_dosen') ?>

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
