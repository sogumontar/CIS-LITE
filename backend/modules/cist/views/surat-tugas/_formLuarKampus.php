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
	   $form = ActiveForm::begin([
          'layout' => 'horizontal',
          'enableAjaxValidation' => true,
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
        $form->field($model, 'nama_kegiatan')->textInput()
    ?>

    <?= 
        $form->field($model, 'agenda')->textarea(['rows' => '3'])->hint('Diisi dengan Rincian Kegiatan');
    ?>

    <!-- TextInput field for tempat -->
    <?= 
    	 $form->field($model, 'tempat')->textarea(['rows' => '3'])
    ?>

    <!-- DatePicker for tanggal berangkat -->
    <?= $form->field($model, 'tanggal_berangkat',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-5',]
        ])->widget(DateTimePicker::className(),
        [
            'options' => ['placeholder' => 'Pilih tanggal dan waktu'],
            'pluginOptions' => [
                'autoclose' => 'true',
                'todayHighlight' => true
            ],
        ])->hint('Format: Y-m-d H:i (contoh 2015-01-31 10:00)'); ?>
    
    <!-- DatePicker for tanggal kembali -->
    <?= $form->field($model, 'tanggal_kembali',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-5',]
        ])->widget(DateTimePicker::className(),
        [
            'options' => ['placeholder' => 'Pilih tanggal dan waktu'],
            'pluginOptions' => [
                'autoclose' => 'true',
                'todayHighlight' => true
            ]
        ]); ?>
    
    <!-- DatePicker for tanggal mulai kegiatan -->
    <?= $form->field($model, 'tanggal_mulai',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-5',]
        ])->widget(DateTimePicker::className(),
        [
            'options' => ['placeholder' => 'Pilih tanggal dan waktu'],
            'pluginOptions' => [
                'autoclose' => 'true',
                'todayHighlight' => true
            ]
        ]); ?>
    
    <!-- DatePicker for tanggal selesai kegiatan -->
    <?= $form->field($model, 'tanggal_selesai',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-5',]
        ])->widget(DateTimePicker::className(),
        [
            'options' => ['placeholder' => 'Pilih tanggal dan waktu'],
            'pluginOptions' => [
                'autoclose' => 'true',
                'todayHighlight' => true
            ]
        ]); ?>

    <?= $form->field($model, 'kembali_bekerja',[
               'horizontalCssClasses' => ['wrapper' => 'col-sm-5',]
        ])->widget(DateTimePicker::className(),
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
 
    <!-- RichText for transportasi -->
    <?= 
        $form->field($model, 'transportasi')->widget(Redactor::className(), ['options' => [
        'minHeight' => 100,
        'class'=>'form-control'
    ], ])
    ?>

    <!-- RichText for desc_surat_tugas -->
    <?= 
    	$form->field($model, 'desc_surat_tugas')->widget(Redactor::className(),
            ['options' => ['minHeight' => 150,], 
            ])->hint('Diisi dengan Rincian Akomodasi, Sumber Dana, dan hal-hal lainnya yang perlu diketahui Sekretaris Rektorat (Tidak dicantumkan dalam surat tugas)');
    ?>

    <!-- RichText for pengalihan tugas -->
    <?= 
        $form->field($model, 'pengalihan_tugas')->widget(Redactor::className(), ['options' => [
        'minHeight' => 100,
    ], ])
    ?>

    <!-- FileInput for lampiran -->
    <div class="form-group field-materi-files">
        <label class="control-label col-sm-2" for="materi-files">Lampiran</label>
        <div class="col-sm-4">
            <div id="file_input">
                <input type="file" class="form-control" name="files[]">
            </div>
            <div>
                <a href="#" onclick="addMoreFiles()">Tambah Lampiran</a>
            </div>
        </div>
    </div>
    <br/>
    
    <?php
        //Get list of atasan
        $arrayAtasan = ArrayHelper::map($modelAtasan, 'pegawai_id', 'nama');
        
        //CheckboxList for list of atasan
        echo $form->field($model, 'atasan')->checkboxList($arrayAtasan);
    ?>
    <br/>

    <div class="form-group" >
        <div class="col-md-1 col-md-offset-2">
            <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
        /**
         * Ajax for peserta DynamicForm
         */
        $this->registerJs(
            "
            $('#surattugas-tanggal_berangkat').on('change', function(e) {
                $('#surattugas-tanggal_mulai').val($(this).val());
            });

            $('#surattugas-tanggal_kembali').on('change', function(e) {
                $('#surattugas-tanggal_selesai').val($(this).val());
            });

            var count = 1;
            $(document).ready(function(){
                addMoreFirst();
            });
            
            function addMoreFirst(){
                $.ajax({
                    url: '".\Yii::$app->urlManager->createUrl(['cist/surat-tugas/pegawai-self'])."',
                    type: 'POST',
                    success: function(data){
                        data = jQuery.parseJSON(data);
                        pegawais = '<option selected=\"selected\" value=\"'+data['pegawai_id']+'\">'+data['nama']+'</option>';
                        addSelf(pegawais);
                    }
                });
            }

            function addSelf(pegawais){
                $('#field_input').append('<div class=\"form-group\"><label class=\"control-label col-sm-2\">Peserta '+count+'</label><div class=\"col-sm-8\"><select id=\"id_pegawai\" class=\"form-control\" name=\"Peserta[][id_pegawai]\" readonly=\"readonly\">'+pegawais+'</select><div class=\"help-block help-block-error \"></div></div></div>');
                count++;
            }

            function addMore(){
                $.ajax({
                    url: '".\Yii::$app->urlManager->createUrl(['cist/surat-tugas/pegawais'])."',
                    type: 'POST',
                    success: function(data){
                        data = jQuery.parseJSON(data);
                        pegawais = '';
                        for(var i = 0; i < data.length; i++){
                            
                            if(count == 1){
                                
                                if(data[i]['pegawai_id'] == ". $pegawai->pegawai_id ."){
                                    pegawais += '<option selected=\"selected\" value=\"'+data[i]['pegawai_id']+'\">'+data[i]['nama']+'</option>';
                                }else{
                                    pegawais += '<option value=\"'+data[i]['pegawai_id']+'\">'+data[i]['nama']+'</option>';
                                }
                            }else{
                                if(i == 0){
                                    pegawais += '<option selected=\"selected\" value=\"empty\">Pilih Peserta</option>';
                                    pegawais += '<option value=\"'+data[i]['pegawai_id']+'\">'+data[i]['nama']+'</option>';
                                }else{
                                    pegawais += '<option value=\"'+data[i]['pegawai_id']+'\">'+data[i]['nama']+'</option>';
                                }
                            }
                        }
                        add(pegawais);
                    }
                });
            }

            function add(pegawais){
                $('#field_input').append('<div class=\"form-group\"><label class=\"control-label col-sm-2\">Peserta '+count+'</label><div class=\"col-sm-8\"><select id=\"id_pegawai\" class=\"form-control\" name=\"Peserta[][id_pegawai]\">'+pegawais+'</select><div class=\"help-block help-block-error \"></div></div></div>');
                count++;
            }

            function addMoreFiles(){
               $('#file_input').append('<input type=file class=form-control name=files[]>');
           }
        ",
        $this::POS_END);
    ?>
</div>
