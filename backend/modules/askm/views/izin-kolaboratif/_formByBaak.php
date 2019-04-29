<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinTambahanJamKolaboratif */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="izin-kolaboratif-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // echo $form->field($model, 'rencana_mulai')->textInput() ?>

    <div class="row">
        <div class="col-md-4 col-sm-4">
            <?= $form->field($model, 'rencana_mulai')->widget(DateTimePicker::className(), [
                'language' => 'en',
                'size' => 'ms',
                'pickButtonIcon' => 'glyphicon glyphicon-time',
                'inline' => false,
                'clientOptions' => [
                    'startView' => 2,
                    'minView' => 2,
                    'maxView' => 2,
                    'pickerPosition' => 'bottom-left',
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd', // if inline = false
                    // 'todayBtn' => true,
                    'startDate' => date("Y-m-d"),
                ]
            ]);?>
        </div>

        <div class="col-md-4 col-sm-4">
            <?= $form->field($model, 'rencana_berakhir')->widget(DateTimePicker::className(), [
                'language' => 'en',
                'size' => 'ms',
                'pickButtonIcon' => 'glyphicon glyphicon-time',
                'inline' => false,
                'clientOptions' => [
                    'startView' => 2,
                    'minView' => 2,
                    'maxView' => 2,
                    'pickerPosition' => 'bottom-left',
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd', // if inline = false
                    // 'todayBtn' => true,
                    'startDate' => date("Y-m-d"),
                ]
            ]);?>
        </div>

        <div class="col-md-4 col-sm-4">
            <?= $form->field($model, 'batas_waktu')->widget(DateTimePicker::className(), [
                'language' => 'en',
                'size' => 'ms',
                'pickButtonIcon' => 'glyphicon glyphicon-time',
                'inline' => false,
                'clientOptions' => [
                    'startView' => 1,
                    'minView' => 0,
                    'maxView' => 1,
                    'pickerPosition' => 'bottom-left',
                    'autoclose' => true,
                    'format' => 'HH:ii',
                    'linkFormat' => 'HH:ii',
                    // 'todayBtn' => true
                ]
            ]);?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <?= $form->field($model, 'status_request_id')->hiddenInput(['value' => 1])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Buat Baru' : 'Selesai Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::a('Batal', ['izin-by-baak-index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
