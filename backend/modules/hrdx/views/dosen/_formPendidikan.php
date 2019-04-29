<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\hrdx\models\Jenjang;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\RiwayatPendidikan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="riwayat-pendidikan-form">

    <?php $form = ActiveForm::begin([
        'id' => 'tambah-dosen',
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

    <?=
        $form->field($model, 'jenjang_id')->dropDownList(
                                                ArrayHelper::map(Jenjang::find()->all(),'jenjang_id','nama'),
                                                ['prompt'=>'=== Pilih Jenjang ===']
        )
    ?>

    <?= $form->field($model, 'universitas')->textInput(['maxlength' => 60]) ?>

    <?= $form->field($model, 'prodi')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'judul_ta')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ipk')->textInput(['maxlength' => 5]) ?>

    <div class="form-group">
        <label class="control-label col-sm-3" for="menugroup-desc"></label>
        <div class="col-sm-5">
            <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Ubah', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
