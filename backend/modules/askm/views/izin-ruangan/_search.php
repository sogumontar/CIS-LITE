<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\search\IzinRuanganSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="izin-ruangan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'izin_ruangan_id') ?>

    <?= $form->field($model, 'rencana_mulai') ?>

    <?= $form->field($model, 'rencana_berakhir') ?>

    <?= $form->field($model, 'desc') ?>

    <?= $form->field($model, 'dim_id') ?>

    <?php // echo $form->field($model, 'staf_id') ?>

    <?php // echo $form->field($model, 'lokasi_id') ?>

    <?php // echo $form->field($model, 'status_request_id') ?>

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
