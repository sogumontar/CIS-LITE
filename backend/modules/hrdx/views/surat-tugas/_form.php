<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use kartik\datetime\DateTimePicker;
use backend\modules\hrdx\assets\HrdxAsset;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\SuratTugas */
/* @var $form yii\widgets\ActiveForm */

$uiHelper = \Yii::$app->uiHelper;
HrdxAsset::register($this);
?>

<div class="surat-tugas-form">

    <?php
        $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-3',
                    'wrapper' => 'col-sm-6',
                    'error' => '',
                    'hint' => '',
                ],
            ],
            //'enableAjaxValidation'=>true,
    ]) ?>

    <?= $form->field($model, 'no_surat_tugas')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tugas')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'pemberi_tugas')->dropDownList(ArrayHelper::map($pemberi_tugas,'strukturJabatan.struktur_jabatan_id','strukturJabatan.deskripsi'),['prompt'=>'']) ?>

    <div class="form-group field-surattugas-pelaksana-tugas">
        <label class="control-label col-sm-3" for="surattugas-tugas">Penerima Tugas</label>
        <div class="col-sm-6">
            <select name="penerima_tugas[]" class="js-example-basic-multiple" multiple="multiple" style="width:100%;">
                <?php
                foreach ($penerima_tugas_1 as $key => $value) {
                    echo "<option value='".$value->pegawai_id."' selected>".$value->nama."</option>";
                }

                foreach ($penerima_tugas_2 as $key => $value) {
                    echo "<option value='".$value->pegawai_id."'>".$value->nama."</option>";
                }
                ?>
            </select>
        <div class="help-block help-block-error "></div>
        </div>

    </div>

    <?= $form->field($model, 'kota_tujuan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lokasi_tugas')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tanggal_berangkat',[
            'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
            ])
            ->widget(DateTimePicker::className(),[
                'name' => 'datetime_400',
                'removeButton' => false,
                'pickerButton' => ['icon' => 'calendar'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii'
                ]
            ]) 
    ?>

    <?= $form->field($model, 'tanggal_kembali',[
            'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
            ])
            ->widget(DateTimePicker::className(),[
                'name' => 'datetime_400',
                'removeButton' => false,
                'pickerButton' => ['icon' => 'calendar'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii'
                ]
            ]) 
    ?>

    <?= $form->field($model, 'tanggal_mulai',[
            'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
            ])
            ->widget(DateTimePicker::className(),[
                'name' => 'datetime_400',
                'removeButton' => false,
                'pickerButton' => ['icon' => 'calendar'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii'
                ]
            ]) 
    ?>

    <?= $form->field($model, 'tanggal_selesai',[
            'horizontalCssClasses' => ['wrapper' => 'col-sm-4',],
            ])
            ->widget(DateTimePicker::className(),[
                'name' => 'datetime_400',
                'removeButton' => false,
                'pickerButton' => ['icon' => 'calendar'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii'
                ]
            ]) 
    ?>

    <?= $form->field($model, 'catatan')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-6">
            <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
  $this->registerJs(
    "$('.js-example-basic-multiple').select2();", 
    View::POS_END);
?>
