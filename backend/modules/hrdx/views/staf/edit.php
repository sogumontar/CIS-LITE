<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

use backend\modules\mref\models\GolonganKepangkatan;
use backend\modules\mref\models\JabatanAkademik;
use backend\modules\mref\models\StatusIkatanKerjaDosen;
use backend\modules\mref\models\Gbk;
use backend\modules\mref\models\RoleDosen;
use backend\modules\hrdx\models\Jenjang;

$uiHelper=\Yii::$app->uiHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\Dosen */

$this->title = 'Ubah Staff: ' . ' ' . $model->staf_id;
$this->params['breadcrumbs'][] = ['label' => 'Staf', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pegawai['nama'], 'url' => ['view', 'id' => $model->staf_id]];
$this->params['breadcrumbs'][] = 'Ubah';
?>

<div class="dosen-form">

    <?php $form = ActiveForm::begin([
        'id' => 'tambah-staff',
        'layout' => 'horizontal',
                                        'fieldConfig' => [
                                            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                                            'horizontalCssClasses' => [
                                                'label' => 'col-sm-3',
                                                'wrapper' => 'col-sm-5',
                                                'error' => '',
                                                'hint' => '',
                                            ],
                                        ],
    ]); ?>  

    <?= $form->field($model, 'staf_role_id', ['horizontalCssClasses' => ['wrapper' => 'col-sm-4']])->dropDownList( 
                                                    ArrayHelper::map($stafRole,'staf_role_id','nama'))
                                               ->label('Posisi'); 
    ?>
    <!-- <?= $form->field($model, 'prodi_id', ['horizontalCssClasses' => ['wrapper' => 'col-sm-4']])->dropDownList( 
                                                    ArrayHelper::map($prodi,'ref_kbk_id','kbk_ind'))
                                               ->label('Program Studi'); 
    ?> -->

    <?= $form->field($model, 'prodi_id', ['horizontalCssClasses' => ['wrapper' => 'col-sm-4']])->dropDownList( 
                                                    ArrayHelper::map($prodi,'ref_kbk_id',function($data){ return isset($data->kbk_ind)?($data->jenjang->nama.'-'.$data->kbk_ind):''; }, 'jenjang.nama'),['prompt' => 'Select...'])->label('Program Studi'); 
    ?>

    <?= $form->field($model, 'aktif_start')->widget(DatePicker::className(),
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


    <?= $form->field($model, 'aktif_end')->widget(DatePicker::className(),
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
    

    <div class="form-group">
        <label class="control-label col-sm-3" for="menugroup-desc"></label>
        <div class="col-sm-5">
            <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
 
