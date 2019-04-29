<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\Redactor;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\Pedoman */
/* @var $form yii\widgets\ActiveForm */
$izin = [1=>'Izin Bermalam', 2=>'Izin Keluar', 3=>'Izin Kolaboratif', 4=>'Izin Ruangan'];
?>

<div class="pedoman-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'judul')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isi')->widget(Redactor::className(), ['options' => [
                 'minHeight' => 100,
        ],])  
    ?>

    <?= $form->field($model, 'jenis_izin')->dropDownList(($izin), ['prompt'=>'Pilih Izin'])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Buat' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
