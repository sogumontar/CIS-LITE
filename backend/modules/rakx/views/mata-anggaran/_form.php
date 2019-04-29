<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\widgets\Redactor;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\MataAnggaran */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mata-anggaran-form">

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

    <!--$form->field($model, 'standar_id',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-4',]
        ])->dropDownList(
            ArrayHelper::map($standar, 'standar_id', function($model){return 'Standar '.$model->nomor.': '.$model->name; }),["prompt"=>"Standar"])
    -->

    <?= $form->field($model, 'kode_anggaran')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->widget(Redactor::className(), ['options' => [
                 'minHeight' => 100,
        ],]) 
    ?>

    <div class="form-group">
        <div class="col-md-1 col-md-offset-2">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div></div>

    <?php ActiveForm::end(); ?>

</div>
