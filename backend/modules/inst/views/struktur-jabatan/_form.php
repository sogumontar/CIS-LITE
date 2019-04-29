<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\StrukturJabatan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="struktur-jabatan-form">

    <?php
        $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    'wrapper' => 'col-sm-8',
                    'error' => '',
                    'hint' => '',
                ],
            ],
            //'enableAjaxValidation'=>true,
    ]) ?>

    <?= $form->field($model, 'jabatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inisial')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent')->dropDownList(
            ArrayHelper::map($parent, 'struktur_jabatan_id', 'jabatan'),["prompt"=>"Atasan"])
    ?>

    <?= $form->field($model, 'is_multi_tenant',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-3',]
        ])->dropDownList(
            ArrayHelper::map($tenant, 'tenant_id', 'tenant_name'),["prompt"=>"Status Tenant", 'onchange'=>'$(this).val()==0?
            $("select#strukturjabatan-mata_anggaran").prop("disabled", false):$("select#strukturjabatan-mata_anggaran").val("0")'
            ])->hint('Single: Hanya bisa dijabat 1 Pejabat dalam 1 waktu<br />Multi Tenant: Bisa lebih dari 1 Pejabat')
    ?>

    <?= $form->field($model, 'mata_anggaran',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-3',]
        ])->dropDownList(
            ArrayHelper::map($mata_anggaran, 'ma_id', 'ma_name'),["prompt"=>"Mata Anggaran", 'onchange'=>'$("select#strukturjabatan-is_multi_tenant").val()==1?
            $(this).val("0"):$(this).val($(this).val())'])->hint('Apakah Jabatan memiliki Mata Anggaran yang memungkinkannya membuat Penganggaran ?')
    ?>

    <?= $form->field($model, 'laporan',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-2',]
        ])->dropDownList(
            ArrayHelper::map($laporan, 'lap_id', 'lap_name'),["prompt"=>"Laporan"])->hint('Apakah Jabatan diharuskan membuat Laporan kepada Atasannya atau tidak ?')
    ?>

    <?= $form->field($model, 'unit_id')->dropDownList(
            ArrayHelper::map($unit, 'unit_id', 'name'),["prompt"=>"Unit"])->label('Unit')
    ?>

    <div class="form-group">
        <div class="col-md-1 col-md-offset-2">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>