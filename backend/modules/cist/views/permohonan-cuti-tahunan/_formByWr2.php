<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\InputWidget;
use yii\base\Widget;
use yii\base\Component;
use yii\base\BaseObject;
use yii\base\Configurable;
use yii\base\ViewContextInterface;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
use common\widgets\Typeahead;
use yii\helpers\Url;
use backend\modules\cist\assets\CistAsset;
use yii\widgets\DetailView;

CistAsset::register($this);
$uiHelper = \Yii::$app->uiHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiTahunan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permohonan-cuti-tahunan-form">
     <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!--STEP HERE!!!!!-->
                <div class="border-box">
                    <div class="stepwizard col-md-6">
                        <div class="stepwizard-row setup-panel">
                            <div class="stepwizard-step col-md-3">
                                <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                                <p>Ajukan Izin</p>
                            </div>
                            <div class="stepwizard-step col-md-3">
                                <a href="#step-2" type="button" class="btn btn-default btn-circle">2</a>
                                <p>HRD</p>
                            </div>
                            <div class="stepwizard-step col-md-3">
                                <a href="#step-3" type="button" class="btn btn-default btn-circle">3</a>
                                <p>Supervisor</p>
                            </div>
                            <div class="stepwizard-step col-md-3">
                                <a href="#step-4" type="button" class="btn btn-default btn-circle">4</a>
                                <p>WR 2</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!--END ROW-->

         <!--FORM START HERE!!!!!-->
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
         ]) ?>
        <div class="row setup-content" id="step-1">
            <div class="col-xs-12">
                <div class="col-md-12">
                    <div class="">
                        <div class="box-header">

                        </div>
                        <div class="box-body">
                            <!--DATE RANGE-->
                            <div class="form-group">
                                <label class="control-label col-sm-3">Waktu Cuti</label>
                                <div class="input-group col-sm-8">
                                    <div id="mdp-demo" style="margin-left: 55px"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3"></label>
                                <div class="input-group col-sm-8">
                                    <?= $form->field($model, 'waktu_pelaksanaan',[
                                        'horizontalCssClasses' => [
                                            'wrapper' => 'col-sm-10',
                                            'label' => 'col-sm-1',
                                        ]
                                    ])->textInput([
                                        'id' => 'altField',
                                    ])->label('')?>

                                </div>
                                <div class="help-block help-block-error "></div>
                            </div>

                            <!--LAMA CUTI-->
                            <div class="form-group">
                                <label class="control-label col-sm-3">Lama Cuti (Hari)</label>
                                <div class="input-group col-sm-8">
                                    <?= $form->field($model, 'lama_cuti',[
                                        'horizontalCssClasses' => [
                                            'wrapper' => 'col-sm-10',
                                            'label' => 'col-sm-1',
                                        ]
                                    ])->textInput([
                                        'id' => 'numberSelected',
                                    ])->label('')?>

                                </div>
                                <div class="help-block help-block-error "></div>
                            </div>

                            <!-- SISA KUOTA -->
                            <div class="form-group">
                                <label class="control-label col-sm-3">Sisa Kuota Cuti Tahunan (Hari) </label>
                                <label class="control-label col-sm-1" style="margin-left: 5px">
                                    <?php
                                    print($kuota);
                                    ?>
                                </label>
                            </div>
                            <br>

                            <!--ALASAN CUTI-->
                            <div class="form-group">
                                <label class="control-label col-sm-3">Alasan Cuti</label>
                                <div class="input-group col-sm-8">
                                    <?= $form->field($model, 'alasan_cuti',[
                                        'horizontalCssClasses' => [
                                            'wrapper' => 'col-sm-10',
                                            'label' => 'col-sm-1',
                                        ]
                                    ])->textarea([
                                        'maxlength' => true,
                                        'rows' => '6',
                                        'placeholder' => 'Isi alasan di sini',
                                    ])->label('')?>

                                </div>
                                <div class="help-block help-block-error "></div>
                            </div>

                            <!--PENGALIHAN TUGAS-->
                            <div class="form-group">
                                <label class="control-label col-sm-3">Pengalihan Tugas</label>
                                <div class="input-group col-sm-8">
                                    <?= $form->field($model, 'pengalihan_tugas',[
                                        'horizontalCssClasses' => [
                                            'wrapper' => 'col-sm-10',
                                            'label' => 'col-sm-1',
                                        ]
                                    ])->textarea([
                                        'maxlength' => true,
                                        'rows' => '6',
                                        'placeholder' => 'Isi pengalihan tugas di sini',
                                    ])->label('') ?>
                                </div>
                            </div>

                            <!--ATASAN-->
                            <div class="form-group">
                                <label class="control-label col-sm-3">Atasan</label>
                                <?php
                                $arrayAtasan = ArrayHelper::map($namaPegawai, 'pegawai_id', 'nama');
                                ?>
                                <div class="input-group col-sm-8">
                                    <?= $form->field($model, 'atasan',[
                                        'horizontalCssClasses' => [
                                            'wrapper' => 'col-sm-10',
                                            'label' => 'col-sm-1',
                                        ]
                                    ])->checkboxList($arrayAtasan,[
                                        'style' => 'margin-left: -20px'
                                    ])->label('')?>

                                </div>
                                <div class="help-block help-block-error "></div>
                            </div>

                            <!--BUTTON SUBMIT-->
                            <div class="form-group">
                                <div class="col-md-4"></div>
                                <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary', 'style' => 'margin-left: -20px']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </section>

</div>
