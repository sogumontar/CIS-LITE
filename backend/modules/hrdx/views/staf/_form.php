<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\Staf */
/* @var $form yii\widgets\ActiveForm */
$uiHelper=\Yii::$app->uiHelper;

use backend\modules\hrdx\models\Jenjang;
?>

<div class="staf-form">

    <?php $form = ActiveForm::begin([
        'id' => 'tambah-staf',
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
                                                    ArrayHelper::map($stafRole,'staf_role_id','nama'), ['prompt'=> 'Select...'])
                                               ->label('Posisi'); 
    ?>

    <?= $form->field($model, 'prodi_id', ['horizontalCssClasses' => ['wrapper' => 'col-sm-4']])->dropDownList( 
                                                    ArrayHelper::map($prodi,'ref_kbk_id', function($data){ return isset($data->kbk_ind)?($data->jenjang->nama.'-'.$data->kbk_ind):''; }, 'jenjang.nama'),['prompt' => 'Select...'])
                                               ->label('Program Studi'); 
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

    <?= $uiHelper->renderContentSubHeader('Pendidikan Terakhir', ['icon' => 'fa fa-pencil']);?>
    <?=$uiHelper->renderLine(); ?>

    <?=
        $form->field($pendMdl, 'jenjang_id')->dropDownList(
                                                ArrayHelper::map($jenjang,'jenjang_id','nama'), ['prompt'=> 'Select...']
        );
    ?>

    <?= $form->field($pendMdl, 'universitas')->textInput(['maxlength' => 60]) ?>

    <?= $form->field($pendMdl, 'jurusan')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($pendMdl, 'judul_ta')->textarea(['rows' => 6]) ?>

    <?= $form->field($pendMdl, 'ipk')->textInput(['maxlength' => 5]) ?>

    <?= $form->field($pendMdl, 'thn_mulai')->textInput(['maxlength' => 5]) ?>

    <?= $form->field($pendMdl, 'thn_selesai')->textInput(['maxlength' => 5]) ?>


    <div class="form-group">
        <label class="control-label col-sm-3" for="menugroup-desc"></label>
        <div class="col-sm-5">
            <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
