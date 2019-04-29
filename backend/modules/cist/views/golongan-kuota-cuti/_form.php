<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\GolonganKuotaCuti */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="golongan-kuota-cuti-form">

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
        ])
    ?>

    <?= $form->field($model, 'nama_golongan',[
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-6',
            'label' => 'col-sm-3',
        ]
    ])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'min_tahun_kerja',[
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-6',
            'label' => 'col-sm-3',
        ]
    ])->textInput() ?>

    <?= $form->field($model, 'max_tahun_kerja',[
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-6',
            'label' => 'col-sm-3',
        ]
    ])->textInput() ?>

    <?= $form->field($model, 'kuota',[
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-6',
            'label' => 'col-sm-3',
        ]
    ])->textInput() ?>

    <div class="form-group">
        <label class="control-label col-sm-3" for="menugroup-desc"></label>
        <div class="col-sm-6">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
