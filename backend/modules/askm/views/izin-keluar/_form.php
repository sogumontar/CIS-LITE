<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;
use backend\modules\askm\models\Dim;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinKeluar */
/* @var $form yii\widgets\ActiveForm */
$datetime = new DateTime();
$datetime->modify('+1 day');
$dim = Dim::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
?>

<div class="izin-keluar-form">

    <?php $form = ActiveForm::begin([
                                'enableAjaxValidation' => true,
                            ]); ?>

    <div class="row">
        <div class="col-md-6 col-sm-6">
        
        <?= $form->field($model, 'rencana_berangkat')->widget(DateTimePicker::className(), [
            'language' => 'en',
            'size' => 'ms',
            'pickButtonIcon' => 'glyphicon glyphicon-time',
            'inline' => false,
            'clientOptions' => [
                'pickerPosition' => 'bottom-left',
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:ii:00', // if inline = false
                // 'todayBtn' => true,
                'startDate' => date($datetime->format("Y-m-d")),
            ]
        ]);?>

        </div>

        <div class="col-md-6 col-sm-6">
            
        <?= $form->field($model, 'rencana_kembali')->widget(DateTimePicker::className(), [
            'language' => 'en',
            'size' => 'ms',
            'pickButtonIcon' => 'glyphicon glyphicon-time',
            'inline' => false,
            'clientOptions' => [
                'pickerPosition' => 'bottom-left',
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:ii:00', // if inline = false
                // 'todayBtn' => true,
                'startDate' => date($datetime->format("Y-m-d")),
            ]
        ]);?>

        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-sm-8">
            <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <?= $form->field($model, 'dim_id')->hiddenInput(['value' => $dim->dim_id])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Buat Baru' : 'Selesai Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::a('Batal', ['ika-by-mahasiswa-index'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
