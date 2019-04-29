<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\datetime\DateTimePicker;
use common\widgets\Redactor;
use yii\helpers\ArrayHelper;
use backend\modules\cist\models\Pejabat;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\SuratTugas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="surat-tugas-form">
    <?php
        //Get atasan
        $arrayAtasan = ArrayHelper::map($modelAtasan, 'pegawai_id', 'nama');

        $form = ActiveForm::begin([
	      'layout' => 'horizontal',
	      'options' => ['enctype' => 'multipart/form-data'],
	      'fieldConfig' => [
	          'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}",
	          'horizontalCssClasses' => [
	             'label' => 'col-sm-2',
	             'wrapper' => 'col-sm-8',
	             'error' => '',
	             'hint' => '',
	          ],
	       ],
	   ]);
	?>

    <!-- TextInput field for agenda -->
    <?= 
        $form->field($model, 'nama_kegiatan')->textInput(['maxlength' => true]) 
    ?>
    <?= 
    	$form->field($model, 'agenda')->textInput(['maxlength' => true]) 
    ?>

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

    <?= $form->field($model, 'files[]')->fileInput(['multiple' => true]) ?>

    <?= $form->field($model, 'atasan')->checkboxList($arrayAtasan); ?>

    <div class="form-group">
        <div class="col-md-1 col-md-offset-2">
            <?= Html::submitButton($model->isNewRecord ? 'Buat' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?= Html::a('Kembali', ['index-pegawai'], ['class' => 'btn btn-primary']) ?>
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
                                pegawais += '<option selected=\"selected\" value=\"'+data[i]['pegawai_id']+'\">'+data[i]['nama']+'</option>';
                            }else{
                                pegawais += '<option value=\"'+data[i]['pegawai_id']+'\">'+data[i]['nama']+'</option>';
                            }
                        }
                        add(pegawais);
                    }
                });
            }
 
            function add(pegawais){
                $('#field_input').append('<div class=\"form-group\"><label class=\"control-label col-sm-2\">Peserta</label><div class=\"col-sm-8\"><select id=\"id_pegawai\" class=\"form-control\" name=\"Peserta[][id_pegawai]\">'+pegawais+'</select><div class=\"help-block help-block-error \"></div></div></div>');
            }
        ",
        $this::POS_END);
    ?>

</div>
