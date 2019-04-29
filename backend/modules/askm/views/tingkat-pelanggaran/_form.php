<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\Redactor;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\TingkatPelanggaran */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tingkat-pelanggaran-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textArea(['rows' => 4]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
