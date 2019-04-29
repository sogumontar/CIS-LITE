<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\Unit */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unit-form">

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

    <?= $form->field($model, 'instansi_id',[
            'horizontalCssClasses' => ['wrapper' => 'col-sm-4',]
        ])->dropDownList(ArrayHelper::map($instansi, 'instansi_id', 'name'),["prompt"=>"Instansi", 'disabled' => (!$model->isNewRecord)?'disabled':false, 'onchange'=>'$.post("'.\Yii::$app->urlManager->createUrl('inst/unit/kepala-by-instansi-list?instansi_id=').'"+$(this).val(),function( data ) {
                            $( "select#unit-kepala" ).html( data );
                        });'])->label('Instansi')
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inisial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'kepala',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-8',]
        ])->dropDownList(
            ArrayHelper::map($struktur_jabatan, 'struktur_jabatan_id', 'jabatan'),["prompt"=>"Kepala"])
    ?>

    <div class="form-group">
        <div class="col-md-1 col-md-offset-2">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
