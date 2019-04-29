<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\datetime\DateTimePicker;
use common\widgets\Redactor;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
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
        $form->field($model, 'agenda')->textarea(['rows' => '3'])->hint('Diisi dengan Rincian Kegiatan.');
    ?>

    <!-- TextInput field for tempat -->
    <?= 
        $form->field($model, 'tempat')->textarea(['rows' => '3'])
    ?>

    <!-- DatePicker for tanggal berangkat -->
    <?= $form->field($model, 'tanggal_berangkat')->widget(DateTimePicker::className(),
        [
            'options' => ['placeholder' => 'Pilih tanggal dan waktu'],
            'pluginOptions' => [
                'autoclose' => 'true',
                'todayHighlight' => true
            ]
        ]); ?>
    
    <!-- DatePicker for tanggal kembali -->
    <?= $form->field($model, 'tanggal_kembali')->widget(DateTimePicker::className(),
        [
            'options' => ['placeholder' => 'Pilih tanggal dan waktu'],
            'pluginOptions' => [
                'autoclose' => 'true',
                'todayHighlight' => true
            ]
        ]); ?>
    
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

    <!-- RichText for desc_surat_tugas -->
    <?= 
        $form->field($model, 'desc_surat_tugas')->widget(Redactor::className(),
            ['options' => ['minHeight' => 100,], 
            ])->hint('Diisi dengan Rincian Akomodasi, Sumber Dana, dan hal-hal lainnya yang perlu untuk diketahui sekretaris rektorat (Tidak dicantumkan di pencetakan surat tugas).');
    ?>

    <!-- RichText for transportasi -->
    <?= 
        $form->field($model, 'transportasi')->widget(Redactor::className(), ['options' => [
        'minHeight' => 100,
    ], ])
    ?>

    <!-- RichText for pengalihan tugas -->
    <?= 
        $form->field($model, 'pengalihan_tugas')->widget(Redactor::className(), ['options' => [
        'minHeight' => 100,
    ], ])
    ?>

    <!-- FileInput for lampiran -->
    <!-- <?php echo $form->field($model, 'files[]')->fileInput(['multiple' => true]) ?> -->

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

    <div class="form-group">
        <div class="col-md-1 col-md-offset-2">
            <?= Html::submitButton($model->isNewRecord ? 'Buat' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?= Html::a('Kembali', ['index-pegawai'], ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
        /**
         * Ajax for peserta DynamicForm
         */
        $this->registerJs(
            "var count = 1;
            $(document).ready(function(){
                addMore();
            });
            
            // function addMore(){
            //     $.get('".Url::toRoute('surat-tugas/pegawais-bawahan')."', { id: '".$pegawai->pegawai_id."' })
            //     .done(function(data){
            //         data = jQuery.parseJSON(data);
            //         pegawais = '';
            //         for(var i = 0; i < data.length; i++){
            //             if(i == 0){
            //                 pegawais += '<option selected=\"selected\" value=\"empty\">Pilih Peserta</option>';
            //                 pegawais += '<option value=\"'+data[i]['pegawai_id']+'\">'+data[i]['nama']+'</option>';
            //             }else{
            //                 pegawais += '<option value=\"'+data[i]['pegawai_id']+'\">'+data[i]['nama']+'</option>';
            //             }
            //         }
            //         add(pegawais);
            //     });
            // }

            function addMore(){
                $.ajax({
                    url: '".\Yii::$app->urlManager->createUrl(['cist/surat-tugas/pegawais-bawahan'])."',
                    type: 'POST',
                    data: { 
                        'id': '".$pegawai->pegawai_id."' 
                    },
                    success: function(data){
                        // alert(data);
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
