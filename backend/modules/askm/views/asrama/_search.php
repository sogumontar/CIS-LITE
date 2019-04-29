<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\search\AsramaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asrama-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'asrama_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'lokasi') ?>

    <?= $form->field($model, 'jumlah_mahasiswa') ?>

    <?= $form->field($model, 'kapasitas') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
