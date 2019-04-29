<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\search\StrukturJabatanHasMataAnggaranSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="struktur-jabatan-has-mata-anggaran-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'struktur_jabatan_has_mata_anggaran_id') ?>

    <?= $form->field($model, 'struktur_jabatan_id') ?>

    <?= $form->field($model, 'mata_anggaran_id') ?>

    <?= $form->field($model, 'tahun_anggaran_id') ?>

    <?= $form->field($model, 'subtotal') ?>

    <?php // echo $form->field($model, 'desc') ?>

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
