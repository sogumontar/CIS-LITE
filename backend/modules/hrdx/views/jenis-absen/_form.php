<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\JenisAbsen */
/* @var $form yii\widgets\ActiveForm */
$uiHelper = \Yii::$app->uiHelper;
?>

<div class="jenis-absen-form">

    <?php $form = ActiveForm::begin([
                'layout' => 'horizontal',
                'id' => 'create-form',
                'enableAjaxValidation' => false,
        ]);
   ?>
    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kuota')->textInput(['maxlength' => true])?>

    <?= $form->field($model, 'satuan')->textInput(['maxlength' => true])?>
   
    <div class="form-group field-userform-autoactive">
        <div class="col-sm-6 col-sm-offset-3">
                <?= Html::submitButton($model->isNewRecord ? 'Add' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
