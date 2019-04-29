<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\KategoriCutiNontahunan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kategori-cuti-nontahunan-form">

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

    <?= $form->field($model, 'name',[
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-8',
            'label' => 'col-sm-3',
        ]
    ])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lama_pelaksanaan',[
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-8',
            'label' => 'col-sm-3',
        ]
    ])->textInput() ?>

    <?= $form->field($model, 'satuan',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-4', 'label' => 'col-sm-3']
        ])->dropDownList(
            ArrayHelper::map($satuan, 'id', 'name'), [
        'prompt' => '- Pilih Satuan -'])
    ?>

    <div class="form-group">
        <label class="control-label col-sm-3" for="menugroup-desc"></label>
        <div class="col-sm-6">
            <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',]) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
