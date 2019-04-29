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
use yii\helpers\VarDumper;
use yii\web\View;

CistAsset::register($this);
$uiHelper = \Yii::$app->uiHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiTahunan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permohonan-izin-form">
    <div class="row">
        <div class="col-md-12">
            <!--STEP HERE!!!!!-->
            <div class="border-box">
                <div class="stepwizard col-md-6">
                    <div class="stepwizard-row setup-panel">
                        <div class="stepwizard-step col-md-4">
                            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                            <p>Ajukan Izin</p>
                        </div>
                        <div class="stepwizard-step col-md-4">
                            <a href="#step-2" type="button" class="btn btn-default btn-circle disabled">2</a>
                            <p>Atasan</p>
                        </div>
                        <div class="stepwizard-step col-md-4">
                            <a href="#step-3" type="button" class="btn btn-default btn-circle disabled">3</a>
                            <p>WR 2</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <hr>

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

        <div class="row">
            <div class="col-sm-2" style="text-align:right;">
                <label>Tanggal Izin</label>
            </div>
            <div class="col-sm-10">
                <div id="mdp-demo"></div>
            </div>
        </div>
        <?= $form->field($model, 'waktu_pelaksanaan')->hiddenInput(['id' => 'altField'])->label('')?>
        <?= $form->field($model, 'lama_cuti')->hiddenInput(['id' => 'numberSelected'])->label('') ?>

        <div class="row">
            <div class="col-sm-2"  style="text-align:right;">
                <label>Sisa Kuota Cuti</label>
            </div>
            <div class="col-sm-10">
                <label id="sisa_kuota"><?php print($sisa_kuota);?> Hari</label>
            </div>
        </div>

        <?= $form->field($model, 'alasan_cuti')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'pengalihan_tugas')->textarea(['rows' => 6]) ?>

        <?php $arrayAtasan = ArrayHelper::map($namaPegawai, 'pegawai_id', 'nama');?>

        <?= $form->field($model, 'atasan')->checkboxList($arrayAtasan) ?>

        <br>
        <div class="form-group" style="text-align:center;">
            <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

</div>

<?php
    $this->registerJs(
        "   
            $('#mdp-demo').multiDatesPicker({
                maxPicks: ($('#sisa_kuota').html()).split(' ')[0],
                numberOfMonths: [1,2],
                altField: '#altField',
                dateFormat: 'yy-mm-dd',
                defaultDate: 3,
                onSelect: function(){
                    $('#numberSelected').val($('#mdp-demo').multiDatesPicker('getDates').length);
                },
                beforeShowDay: $.datepicker.noWeekends,
            });
        ",
    View::POS_END);
?>
