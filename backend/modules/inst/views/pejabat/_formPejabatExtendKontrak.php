<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;


/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\Pegawai */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pegawai-form">

    <?php $form = ActiveForm::begin([
                    'options' => ['enctype' => 'multipart/form-data'],
                    'layout' => 'horizontal',
                    'id' => 'add-menu-group',
                    ]); ?>

    <?= $form->field($model, 'pegawai_id',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-4',]
        ])->dropDownList(
            ArrayHelper::map($pegawai, 'pegawai_id', 'nama'),["prompt"=>"Pegawai", 'disabled' => 'disabled'])->label('Pegawai')
    ?>

    <?= $form->field($model, 'struktur_jabatan_id',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-4',]
        ])->dropDownList(
            ArrayHelper::map($struktur_jabatan, 'struktur_jabatan_id', function($data){
                return $data['jabatan'].' - '.$data['instansi']->inisial;
            }),["prompt"=>"Jabatan", 'disabled' => 'disabled'])->label('Jabatan')
    ?>

    <?= $form->field($model, 'awal_masa_kerja')->widget(DatePicker::className(),
                [
                    'options' => ['class' => 'form-control', 'disabled' => 'disabled'],
                    'dateFormat' => 'yyyy-MM-dd',
                    'clientOptions'=>
                        [
                            'changeMonth'=>'true',
                            'changeYear'=>'true',
                            'yearRange'=>"-10:+10",
                        ],
                    //'options'=>['size'=>15,'changeMonth'=>'true','class'=>'form-control']
                ])->hint('Format: yyyy-mm-dd (contoh 2015-01-31)') ?>

    <?= $form->field($model, 'akhir_masa_kerja')->widget(DatePicker::className(),
                [
                    'options' => ['class' => 'form-control'],
                    'dateFormat' => 'yyyy-MM-dd',
                    'clientOptions'=>
                        [
                            'changeMonth'=>'true',
                            'changeYear'=>'true',
                            'yearRange'=>"-10:+10",
                        ],
                    //'options'=>['size'=>15,'changeMonth'=>'true','class'=>'form-control']
                ])->hint('Format: yyyy-mm-dd (contoh 2015-01-31)') ?>

    <?= $form->field($model, 'no_sk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file_sk')->fileInput()->hint('Ekstensi .pdf') ?>

    <div class="form-group">
            <label class="control-label col-sm-3" for="menugroup-desc"></label>
            <div class="col-sm-6">
                <?= Html::submitButton($model->isNewRecord ? 'Perbaharui' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
