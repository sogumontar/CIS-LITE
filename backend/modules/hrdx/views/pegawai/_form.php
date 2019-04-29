<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\mref\models\Agama;
use backend\modules\mref\models\JenisKelamin;
use backend\modules\mref\models\GolonganDarah;
use backend\modules\mref\models\Kabupaten;
use backend\modules\mref\models\StatusMarital;
use backend\modules\mref\models\StatusIkatanKerjaPegawai;
use backend\modules\mref\models\StatusAktifPegawai;
use yii\jui\DatePicker;



/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\Pegawai */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pegawai-form">

    <?php $form = ActiveForm::begin([
                    'layout' => 'horizontal',
                    'id' => 'add-menu-group',
                    'enableAjaxValidation' => true,

                    ]); ?>

    <?= $form->field($model, 'user_id')->textInput(['maxlength' => 45,'style' =>'width:250px'])?>

    <?= $form->field($model, 'nip')->textInput(['maxlength' => 45,'style' =>'width:250px'])?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => 135]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => 3,'style' =>'width:80px'])->label('Inisial') ?>

    <?= $form->field($model, 'tempat_lahir')->textInput(['maxlength' => 60,'style' =>'width:250px']) ?>

    <?= $form->field($model, 'tgl_lahir')->widget(DatePicker::className(),
                [
                    'options' => ['class' => 'form-control'],
                    'dateFormat' => 'yyyy-MM-dd',
                    'clientOptions'=>
                        [
                            'changeMonth'=>'true',
                            'changeYear'=>'true',
                            'yearRange'=>"-100:date('Y')",
                        ],
                    //'options'=>['size'=>15,'changeMonth'=>'true','class'=>'form-control']
                ])->hint('Format: yyyy-mm-dd (contoh 2015-01-31)') ?>

    <?=
        $form->field($model, 'agama_id')->dropDownList(
                                                ArrayHelper::map(Agama::find()->all(),'agama_id','nama'),
                                                ['prompt'=>'Select...',
                                                 'style'=> 'width: 250px'
                                                ]
        )
    ?>

    <?=
        $form->field($model, 'jenis_kelamin_id')->dropDownList(
                                                ArrayHelper::map(JenisKelamin::find()->all(),'jenis_kelamin_id','nama'),
                                                ['prompt'=>'Select...',
                                                 'style'=> 'width: 90px'
                                                ]
        )
    ?>

    <?=
        $form->field($model, 'golongan_darah_id')->dropDownList(
                                                ArrayHelper::map(GolonganDarah::find()->all(),'golongan_darah_id','nama'),
                                                ['prompt'=>'Select...',
                                                 'style'=> 'width: 90px'
                                                ]
        )
    ?>

    <?= $form->field($model, 'telepon')->textInput(['maxlength' => 12,'style' =>'width:150px']) ?>
    <?= $form->field($model, 'hp')->textInput(['maxlength' => 12,'style' =>'width:150px']) ?>

    <?= $form->field($model, 'alamat')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'kecamatan')->textInput(['maxlength' => 150,'style' =>'width:250px']) ?>

    <?=
        $form->field($model, 'kabupaten_id')->dropDownList(
                                                ArrayHelper::map(Kabupaten::find()->all(),'kabupaten_id','nama'),
                                                ['prompt'=>'Select...',
                                                 'style'=> 'width: 250px'
                                                ]
        )
    ?>

    <?= $form->field($model, 'kode_pos')->textInput(['maxlength' => 5,'style' =>'width:80px']) ?>

    <?= $form->field($model, 'no_ktp')->textInput(['maxlength' => 16,'style' =>'width:250px']) ?>

    <?=
        $form->field($model, 'status_marital_id')->dropDownList(
                                                ArrayHelper::map(StatusMarital::find()->all(),'status_marital_id','desc'),
                                     
                                                ['prompt'=>'Select...',
                                                 'style'=> 'width: 250px'
                                                ]
        )
    ?>

    <?=
        $form->field($model, 'status_ikatan_kerja_pegawai_id')->dropDownList(
                                                ArrayHelper::map(StatusIkatanKerjaPegawai::find()->all(),'status_ikatan_kerja_pegawai_id','nama'),
                                                ['prompt'=>'Select...',
                                                 'style'=> 'width: 250px'
                                                ]
        )
    ?>

    <?=
        $form->field($model, 'status_aktif_pegawai_id')->dropDownList(
                                                ArrayHelper::map(StatusAktifPegawai::find()->all(),'status_aktif_pegawai_id','desc'),
                                                ['prompt'=>'Select...',
                                                 'style'=> 'width: 250px'
                                                ]
        )
    ?>

    <?= $form->field($model, 'tanggal_masuk')->widget(DatePicker::className(),
                [
                    'options' => ['class' => 'form-control'],
                    'dateFormat' => 'yyyy-MM-dd',
                    'clientOptions'=>
                        [
                            'changeMonth'=>'true',
                            'changeYear'=>'true',
                            'yearRange'=>"-25:date('Y')",
                        ],
                    //'options'=>['size'=>15,'changeMonth'=>'true','class'=>'form-control']
                ])->hint('Format: yyyy-mm-dd (contoh 2015-01-31)') ?>

    <?= $form->field($model, 'tanggal_keluar')->widget(DatePicker::className(),
                [
                    'options' => ['class' => 'form-control'],
                    'dateFormat' => 'yyyy-MM-dd',
                    'clientOptions'=>
                        [
                            'changeMonth'=>'true',
                            'changeYear'=>'true',
                            'yearRange'=>"-25:date('Y')",
                        ],
                   // 'options'=>['size'=>15,'changeMonth'=>'true','class'=>'form-control']
                ])->hint('Format: yyyy-mm-dd (contoh 2015-01-31)') ?>

    <div class="form-group">
            <label class="control-label col-sm-3" for="menugroup-desc"></label>
            <div class="col-sm-6">
                <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
