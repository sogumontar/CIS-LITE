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

    <hr>

    <?php $form = ActiveForm::begin() ?>

    <?= $form->field($model, 'kuota')->textInput(['style' => 'width:100px']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Generate' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
