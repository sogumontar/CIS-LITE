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
use backend\modules\cist\models\KategoriCutiNontahunan;
use backend\modules\cist\models\WaktuRequestIzin;
use common\widgets\Typeahead;
use yii\helpers\Url;
use backend\modules\cist\assets\CistAsset;
use yii\widgets\DetailView;

CistAsset::register($this);
$uiHelper = \Yii::$app->uiHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiNontahunan*/
/* @var $form yii\widgets\ActiveForm */
?>
<div class="permohonan-izin-form">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!--STEP HERE!!!!!-->
                <div class="border-box">
                    <div class="stepwizard col-md-6">
                        <div class="stepwizard-row setup-panel">
                            <div class="stepwizard-step col-md-3">
                                <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                                <p>Ajukan Cuti</p>
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
                            <div class="form-group">
                                <label class="control-label col-sm-3">Jenis Cuti</label>
                                <div class="input-group col-sm-8">
                                    <?= $form->field($model, 'kategori_id',[
                                        'horizontalCssClasses' => [
                                            'wrapper' => 'col-sm-6',
                                            'label' => 'col-sm-1',
                                        ]
                                    ])->dropDownList(
                                        ArrayHelper::map(KategoriCutiNontahunan::find()->where('deleted != 1')->all(),'kategori_cuti_nontahunan_id', 'name'),
                                        [
                                            'prompt' => '- Pilih Kategori -',
                                            'id' => 'cat',
                                            'onchange' => '$.get("' .Url::toRoute('permohonan-cuti-nontahunan/kategori-cuti'). '", {
                                                kategori_id: $(this).val() })
                                                .done(function(data){
                                                    $("#diff").val(data);
                                                })',
                                        ]
                                    )->label('') ?>
                                </div>
                                <div class="help-block help-block-error "></div>
                            </div>

                            <!--TANGGAL MULAI-->
                            <div class="form-group">
                                <label class="control-label col-sm-3">Tanggal Mulai</label>
                                <div class="input-group col-sm-8">
                                    <div id="mdp-demoe" style="margin-left: 55px"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3"></label>
                                <div class="input-group col-sm-8">
                                    <?= $form->field($model, 'tgl_mulai',[
                                        'horizontalCssClasses' => [
                                            'wrapper' => 'col-sm-10',
                                            'label' => 'col-sm-1',
                                        ]
                                    ])->textInput([
                                        'id' => 'start',
                                    ])->label('')?>
                                </div>
                                <div class="help-block help-block-error "></div>
                            </div>

                            <!--TANGGAL AKHIR-->
                            <div class="form-group">
                                <label class="control-label col-sm-3">Tanggal Akhir</label>
                                <div class="input-group col-sm-8">
                                    <div id="mdp-demos" style="margin-left: 55px"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3"></label>
                                <div class="input-group col-sm-8">
                                    <?= $form->field($model, 'tgl_akhir',[
                                        'horizontalCssClasses' => [
                                            'wrapper' => 'col-sm-10',
                                            'label' => 'col-sm-1',
                                        ]
                                    ])->textInput([
                                        'id' => 'end',
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
                                        'id' => 'diff',
                                        'disabled' => true,
                                    ])->label('') ?>
                                </div>
                                <div class="help-block help-block-error "></div>
                            </div>

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
                                    ])->label('') ?>
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
                                <div class="help-block help-block-error "></div>
                            </div>
                            <br>

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
