<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use dosamigos\datetimepicker\DateTimePicker;
use backend\modules\askm\models\Pegawai;
use backend\modules\askm\models\Dim;
use backend\modules\askm\models\StatusRequest;
use common\widgets\Typeahead;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinBermalam */
/* @var $form yii\widgets\ActiveForm */
$pegawai = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
// $dim = Dim::find()->where('deleted != 1')->andWhere(['nim' => ''])->one();
?>

<div class="izin-bermalam-form">

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
                'startDate' => date("Y-m-d"),
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
                'startDate' => date("Y-m-d"),
            ]
        ]);?>

        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-sm-8">
            <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-sm-8">
            <?= $form->field($model, 'tujuan')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'dim_id')->widget(Typeahead::classname(),[
                'attribute' => 'dim_id',
               'withSubmitButton' => false,
               
               'template' => "<p style='padding:4px'>{{data}}</p>",
               'htmlOptions' => ['class' => 'typeahead', 'placeholder' => 'NIM atau Nama','required'=>true],
               'options' => [
                    'hint' => false,
                    'highlight' => true,
                    'minLength' => 1
               ], 
               'sourceApiBaseUrl' => Url::toRoute(['/askm/izin-bermalam/list-mahasiswa']),
               
                
            ])->label(false) ?>
        </div>
    </div>

    <?= $form->field($model, 'status_request_id')->hiddenInput(['value' => 1])->label(false) ?>

    <?= $form->field($model, 'deleted')->hiddenInput(['value' => 0])->label(false) ?>

    <?= $form->field($model, 'deleted_at')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'deleted_by')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'created_by')->hiddenInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'updated_by')->hiddenInput(['maxlength' => true])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Buat Baru' : 'Selesai Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::a('Batal', ['index-admin'], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
