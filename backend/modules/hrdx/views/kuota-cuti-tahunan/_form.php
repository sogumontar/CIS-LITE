<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\KuotaCutiTahunan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kuota-cuti-tahunan-form">

    <?php $form = ActiveForm::begin([
                'layout' => 'horizontal',
                'id' => 'create-form',
                'enableAjaxValidation' => false,
        ]);
   ?>

    <?= $form->field($model, 'lama_bekerja')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kuota')->textInput(['maxlength' => true])->hint('satuan hari') ?>

    <?= $form->field($model, 'satuan')->textInput(['maxlength' => true])?>
   
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-3">
                <?= Html::submitButton($model->isNewRecord ? 'Add' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
