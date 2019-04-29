<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInputAsset;
use common\widgets\Redactor;

MaskedInputAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\Program */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="program-form">

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

    <?= $form->field($model, 'diusulkan_oleh',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-6',]
        ])->dropDownList(
            ArrayHelper::map($pengusul, 'pejabat_id', function($pengusul){ return $pengusul->pegawai->nama.' - '.$pengusul->strukturJabatan->jabatan; }),['options' => [$pengusul[0]->pejabat_id => ['Selected'=>'selected']],])->label('Pengusul')->hint('Pilih Jabatan yang dimiliki sebagai pengusul dari program')
    ?>

    <?= $form->field($model, 'rencana_strategis_id',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-8',]
        ])->dropDownList(
            ArrayHelper::map($rencana_strategis, 'rencana_strategis_id', function($model){return $model->nomor.' '.$model->name;}),["prompt"=>"--- Rencana Strategis ---"])->label('Rencana Strategis')
    ?>

    <?= $form->field($model, 'struktur_jabatan_has_mata_anggaran_id',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-8',]
        ])->dropDownList(
            ArrayHelper::map($struktur_jabatan_has_mata_anggaran, 'struktur_jabatan_has_mata_anggaran_id', function($model){return $model->strukturJabatan->jabatan.' - '.$model->mataAnggaran->kode_anggaran.' '.$model->mataAnggaran->name;}),["prompt"=>"--- Mata Anggaran ---", 'disabled' => $model->isNewRecord?false:true])->label('Mata Anggaran')
    ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'tujuan')->widget(Redactor::className(), ['options' => [
                 'minHeight' => 100,
        ],]) 
    ?>

    <?= $form->field($model, 'sasaran')->widget(Redactor::className(), ['options' => [
                 'minHeight' => 100,
        ],]) 
    ?>

    <?= $form->field($model, 'target')->widget(Redactor::className(), ['options' => [
                 'minHeight' => 100,
        ],]) 
    ?>

    <?= $form->field($model, 'desc')->widget(Redactor::className(), ['options' => [
                 'minHeight' => 100,
        ],])  
    ?>

    <?= $form->field($model, 'waktu')->inline(true)->checkboxList(ArrayHelper::map($waktu, 'bulan_id', 'name'))->label('Waktu Pelaksanaan') ?>

    <?= $form->field($model, 'volume')->textInput() ?>

    <?= $form->field($model, 'satuan_id',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-6',]
        ])->dropDownList(
            ArrayHelper::map($satuan, 'satuan_id', 'name'),["prompt"=>"--- Satuan ---"])->label('Satuan')->hint(Html::a('Tambah Satuan', ['satuan/satuan-add'], ['target' => '_blank']))
    ?>

    <?= $form->field($model, 'harga_satuan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jumlah')->textInput(['maxlength' => true, 'readOnly'=> true]) ?>

    <div class="form-group">
        <div class="col-md-1 col-md-offset-2">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div></div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$this->registerJs("
    $('#program-harga_satuan').inputmask('currency', {radixPoint:'.', prefix: 'Rp ', 'autoUnmask' : true, removeMaskOnSubmit: true});
    $('#program-harga_satuan').keypress(function(event){
        isNumber(event);
    });
    $('#program-jumlah').inputmask('currency', {radixPoint:'.', prefix: 'Rp ', 'autoUnmask' : true, removeMaskOnSubmit: true});
    $('#program-jumlah').keypress(function(event){
        isNumber(event);
    });
    function isNumber(event){
        var charCode = event.which;
        // backspace & delete
        if (charCode == 46 || charCode == 8) {
            // nothing
        }else{
            // dot(titik) & space(spasi)
            if (charCode === 190 || charCode === 32) {
                event.preventDefault();
            }
            // other than number 0 - 9
            if (charCode < 48 || charCode > 57) {
                event.preventDefault();
            }
        }
        return true;
    }

    var setJumlah = function() {
        var volume = $('#program-volume').val();
        var harga = $('#program-harga_satuan').val();
        var jumlah = volume * harga;
        $('#program-jumlah').val(jumlah);
    };

    $('#program-harga_satuan').on('keyup', function() {
        setJumlah();
    });
    $('#program-volume').on('keyup', function() {
        setJumlah();
    });
");
?>