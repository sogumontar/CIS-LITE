<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;
use backend\modules\askm\models\Lokasi;
use backend\modules\askm\models\Dim;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinTambahanJamKolaboratif */
/* @var $form yii\widgets\ActiveForm */
$datetime = new DateTime();
$datetime->modify('+1 day');
$dim = Dim::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
?>

<div class="izin-ruangan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // echo $form->field($model, 'rencana_mulai')->textInput() ?>

    <div class="row">
        <div class="col-md-6 col-sm-6">
            
            <?= $form->field($model, 'rencana_mulai')->widget(DateTimePicker::className(), [
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
            <?= $form->field($model, 'rencana_berakhir')->widget(DateTimePicker::className(), [
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
        <div class="col-md-8">
            <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'lokasi_id')->dropDownList(ArrayHelper::map(Lokasi::find()->where('deleted!=1')->all(), 'lokasi_id', 'name'), ['prompt'=>'Pilih Ruangan'])?>
        </div>
    </div>

    <?= $form->field($model, 'dim_id')->hiddenInput(['value' => $dim->dim_id])->label(false) ?>

    <?= $form->field($model, 'status_request_id')->hiddenInput(['value' => 1])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Buat Baru' : 'Selesai Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::a('Batal', ['index-mahasiswa'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
