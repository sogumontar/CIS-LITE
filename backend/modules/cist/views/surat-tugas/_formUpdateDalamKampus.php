<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\datetime\DateTimePicker;
use common\widgets\Redactor;
use yii\helpers\ArrayHelper;
use backend\modules\cist\models\Pejabat;
use backend\modules\cist\models\Pegawai;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\SuratTugas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="surat-tugas-form">
    <?php
        //Get atasan
        $arrayAtasan = ArrayHelper::map($modelSisaAtasan, 'pegawai_id', 'nama');
    ?>

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
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
    ]); ?>

    <!-- TextInput field for agenda -->
    <?= $form->field($model, 'nama_kegiatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agenda')->textInput(['maxlength' => true]) ?>

    <!-- DatePicker for tanggal mulai kegiatan -->
    <?= $form->field($model, 'tanggal_mulai')->widget(DateTimePicker::className(),
        [
            'options' => ['placeholder' => 'Pilih tanggal dan waktu'],
            'pluginOptions' => [
                'autoclose' => 'true',
                'todayHighlight' => true
            ]
        ]); ?>
    
    <!-- DatePicker for tanggal selesai kegiatan -->
    <?= $form->field($model, 'tanggal_selesai')->widget(DateTimePicker::className(),
        [
            'options' => ['placeholder' => 'Pilih tanggal dan waktu'],
            'pluginOptions' => [
                'autoclose' => 'true',
                'todayHighlight' => true
            ]
        ]); ?>

    <?= $form->field($model, 'kembali_bekerja')->widget(DateTimePicker::className(),
        [
            'options' => ['placeholder' => 'Pilih tanggal dan waktu'],
            'pluginOptions' => [
                'autoclose' => 'true',
                'todayHighlight' => true
            ]
        ]); ?>

    <?= '<label class="control-label col-sm-2">List Peserta:</label><br/>'; ?>
    <?php
        $idx = 1;
        foreach($modelAssignee as $data){
            $peserta = Pegawai::find()->where(['pegawai_id' => $data->id_pegawai])->one();
            echo("
                <div>
                    <p class='col-sm-8'>". $idx . ". " . $peserta->nama . "</p>" . Html::a('Hapus', ['delete-peserta', 'id' => $peserta->pegawai_id, 'surattugas' => $model->surat_tugas_id], ['class' => 'btn btn-danger']) . 
                "</div>");
            $idx++;
        }
    ?>

    <!-- DynamicForm for peserta -->
    <div class="peserta-form">
        <div id="field_input"></div>
        <div class="form-group">
            <div class="col-md-3 col-md-offset-2">
                <a href="#" onclick="addMore()">Tambah Peserta</a>
            </div>
        </div>
    </div>
    <br/>
    
    <?= $form->field($model, 'desc_surat_tugas')->widget(Redactor::className(), ['options' => [
        'minHeight' => 100,
    ], ]) ?>

    <?= $form->field($model, 'pengalihan_tugas')->widget(Redactor::className(), ['options' => [
        'minHeight' => 100,
    ], ]) ?>

    <?=  '<label class="control-label col-sm-2">List Lampiran:</label><br/>'; ?>
    <?php
        $idx = 1;
        echo("<div class='col-sm-10' style='margin-top: -12px;'>");
        foreach($modelLampiran as $data){
            echo("
            <div>
            <p class='col-sm-8'>". $idx . ". " . Html::a($data->nama_file, ['download-attachments', 'id' => $data->surat_tugas_file_id]) . "</p>" . Html::a('Hapus', ['delete-file', 'id' => $data->surat_tugas_file_id, 'surattugas' => $model->surat_tugas_id], ['class' => 'btn btn-danger']) . 
            "</div>");
            $idx++;
        }
        echo("</div>");
    ?>
    <?= $form->field($model, 'files[]')->fileInput(['multiple' => true]) ?>

    <?=  '<label class="control-label col-sm-2">List Atasan:</label><br/>'; ?>
    <?php
        $idx = 1;
        echo("<div class='col-sm-10' style='margin-top: -12px;'>");
        foreach($modelAssigned as $data){
            $atasan = Pegawai::find()->where(['pegawai_id' => $data->id_pegawai])->one();
            echo("
                <div>
                    <p class='col-sm-8'>". $idx . ". " . $atasan->nama . "</p>" . Html::a('Hapus', ['delete-atasan', 'id' => $atasan->pegawai_id, 'surattugas' => $model->surat_tugas_id], ['class' => 'btn btn-danger']) .
                "</div>");
                $idx++;
        }
        echo("</div>");
    ?>

    <?= $form->field($model, 'atasan')->checkboxList($arrayAtasan) ?>

    <div class="form-group">
        <div class="col-md-1 col-md-offset-2">
            <?= Html::submitButton($model->isNewRecord ? 'Buat' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']) ?>
        </div>    
        <?= Html::a('Kembali', ['view-dosen', 'id' => $model->surat_tugas_id], ['class' => 'btn btn-primary', 'style' => '']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
        $this->registerJs(
            "$(document).ready(function(){
                addMore();
            });
            
            function addMore(){
                $.ajax({
                    url: '".\Yii::$app->urlManager->createUrl(['cist/surat-tugas/pegawais'])."',
                    type: 'POST',
                    success: function(data){
                        data = jQuery.parseJSON(data);
                        pegawais = '';
                        for(var i = 0; i < data.length; i++){
                            if(i == 0){
                                pegawais += '<option selected=\"selected\" value=\"empty\">Pilih Peserta</option>';
                                pegawais += '<option value=\"'+data[i]['pegawai_id']+'\">'+data[i]['nama']+'</option>';
                            }else{
                                pegawais += '<option value=\"'+data[i]['pegawai_id']+'\">'+data[i]['nama']+'</option>';
                            }
                        }
                        add(pegawais);
                    }
                });
            }

            function add(pegawais){
                $('#field_input').append('<div class=\"form-group\"><label class=\"control-label col-sm-2\">Tambah Peserta</label><div class=\"col-sm-8\"><select id=\"id_pegawai\" class=\"form-control\" name=\"Peserta[][id_pegawai]\">'+pegawais+'</select><div class=\"help-block help-block-error \"></div></div></div>');
            }
        ",
        $this::POS_END);
    ?>

</div>
