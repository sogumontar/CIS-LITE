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
use yii\web\View;

CistAsset::register($this);
$uiHelper = \Yii::$app->uiHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiNontahunan*/
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

    <?= $form->field($model, 'kategori_id')->dropDownList(ArrayHelper::map(KategoriCutiNontahunan::find()->where('deleted != 1')->all(),'kategori_cuti_nontahunan_id', 'name'), [
        'prompt' => '- Pilih Kategori -',
        'id' => 'cat',
        'onchange' => '
            $.get("' .Url::toRoute('permohonan-cuti-nontahunan/kategori-cuti'). '", {
            kategori_id: $(this).val() })
            .done(function(data){
                if(data){
                    data = jQuery.parseJSON(data);
                    $("#diff").val(data["lama_pelaksanaan"]+","+data["satuan"]);
                    $("#durasi_label").html(data["lama_pelaksanaan"]+(data["satuan"]==2?" Bulan":" Hari"));
                }
            })',
    ]) ?>

    <div class="row">
        <div class="col-sm-2" style="text-align:right;">
            <label>Lama Cuti</label>
        </div>
        <div class="col-sm-2">
            <label id="durasi_label">0</label>
        </div>
    </div>
    <?= $form->field($model, 'lama_cuti',[
        'horizontalCssClasses' => [
            'wrapper' => 'col-sm-2',
        ]
    ])->hiddenInput(['id' => 'diff','disabled' => true])->label('') ?>

    <div class="row">
        <div class="col-sm-2" style="text-align:right;">
            <label>Tanggal Mulai</label>
        </div>
        <div class="col-sm-10">
            <div id="mdp-demos"></div>
        </div>
    </div>
    <?= $form->field($model, 'tgl_mulai')->hiddenInput(['id' => 'start', 'style' => 'width:150px'])->label('')?>

    <div class="row">
        <div class="col-sm-2" style="text-align:right;">
            <label>Tanggal Masuk</label>
        </div>
        <div class="col-sm-10">
            <div id="mdp-demoe"></div>
        </div>
        <!-- <div class="col-sm-6">
            <p id="end_label" class="help-block "></p>
        </div> -->
    </div>
    <?= $form->field($model, 'tgl_akhir')->hiddenInput(['id' => 'end', 'style' => 'width:150px'])->label('')?>

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
        "$('#mdp-demos').multiDatesPicker({
        altField: '#start',
        dateFormat: 'yy-mm-dd',
        minDate: null,
        maxPicks: 1,
        beforeShowDay: $.datepicker.noWeekends,
        onSelect: function(dateStr) {
          var date = $(this).datepicker('getDate');
          var date2 = $(this).datepicker('getDate');
          $('#mdp-demos').multiDatesPicker('resetDates').multiDatesPicker('addDates', [date]);
          $('#start').val(date.getFullYear()+'-'+(date.getMonth()+1)+'-'+date.getDate());
          var dif = parseInt($('#diff').val().split(',')[0]);
          var sat = parseInt($('#diff').val().split(',')[1]);
          var count = 0;
            if(sat==2){
              date2.setMonth(date2.getMonth()+dif);
            }else{
              while(count < dif){
                    date2.setDate(date2.getDate() + 1);
                    //if(date2.getDay() != 0 && date2.getDay() != 6){
                       //Date.getDay() gives weekday starting from 0(Sunday) to 6(Saturday)
                       count++;
                    //}
                }  
            }
            if(date2.getDay() == 0)
                date2.setDate(date2.getDate() + 1);
            else if(date2.getDay() == 6)
                date2.setDate(date2.getDate() + 2);
            var date3 = $(this).datepicker('getDate');
            date3.setDate(date3.getDate() + 1);
          $('#mdp-demoe').multiDatesPicker('destroy');
          $('#mdp-demoe').multiDatesPicker('resetDates').multiDatesPicker({
                altField: '#end',
                dateFormat: 'yy-mm-dd',
                minDate: date3,
                maxPicks: 1,
                beforeShowDay: $.datepicker.noWeekends,
                addDates: [date2],
                defaultDate: date2,
                // onSelect: function(dateStr) {
                //     var date2 = $(this).datepicker('getDate');
                //     $('#end_label').html(date2.getDate()  + \"-\" + (date2.getMonth()+1) + \"-\" + date2.getFullYear());
                // }
            });
          //$('#end_label').html(date2.getDate()  + \"-\" + (date2.getMonth()+1) + \"-\" + date2.getFullYear());
        }
      });
      "
    ,
    View::POS_END);
?>
