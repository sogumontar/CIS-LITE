<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\admin\models\AuthenticationMethod */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authentication-method-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'server_address')->textInput(['maxlength' => 45]) ?>

    <?= $form->field($model, 'authentication_string')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'desc')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
