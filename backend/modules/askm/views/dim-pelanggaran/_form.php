<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datetimepicker\DateTimePicker;
use backend\modules\askm\models\Pembinaan;
use backend\modules\askm\models\PoinPelanggaran;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\DimPelanggaran */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dim-pelanggaran-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6 col-sm-6">
            <?= $form->field($model, 'poin_id')->dropDownList(ArrayHelper::map(PoinPelanggaran::find()->where('deleted!=1')->all(), 'poin_id', 'name'), ['prompt'=>'Poin Pelanggaran', 'disabled' => !$model->isNewRecord,])?>
        </div>
        <div class="col-md-6 col-sm-6">
            <?= $form->field($model, 'pembinaan_id')->dropDownList(ArrayHelper::map(Pembinaan::find()->where('deleted!=1')->all(), 'pembinaan_id', 'name'), ['prompt'=>'Pilih Pembinaan', 'disabled' => !$model->isNewRecord,])?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-6">
            <?= $form->field($model, 'desc_pelanggaran')->textarea(['rows' => 4]) ?>
        </div>
        <div class="col-md-6 col-sm-6">
            <?= $form->field($model, 'desc_pembinaan')->textarea(['rows' => 4]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-6">
            <?= $form->field($model, 'tanggal')->widget(DateTimePicker::className(), [
                'language' => 'en',
                'size' => 'ms',
                'pickButtonIcon' => 'glyphicon glyphicon-time',
                'inline' => false,
                'clientOptions' => [
                    'minView' => 2,
                    'pickerPosition' => 'bottom-left',
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd', // if inline = false
                    'todayBtn' => true,
                ]
            ]);?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
