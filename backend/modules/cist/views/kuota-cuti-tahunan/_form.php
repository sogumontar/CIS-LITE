<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\widgets\Typeahead;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\KuotaCutiTahunan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kuota-cuti-tahunan-form">

    <?php
    $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}",
            'horizontalCssClasses' => [
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-8',
                'error' => '',
                'hint' => '',
            ],
        ],
    ]) ?>
    <?= $form->field($model, 'jumlah_libur',[
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-6',
            'label' => 'col-sm-3',
        ]
    ])->textInput(['placeholder' => 'Masukkan Jumlah Libur Cuti Bersama Nasional'])->label('Jumlah Libur Cuti') ?>

    <div class="form-group">
        <label class="control-label col-sm-3" for="menugroup-desc"></label>
        <div class="col-sm-6">
            <?= Html::submitButton($model->isNewRecord ? 'Generate' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
