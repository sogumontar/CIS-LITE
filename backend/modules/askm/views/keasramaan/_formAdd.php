<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\widgets\Typeahead;
use backend\modules\askm\models\Pegawai;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\KeasramaanPegawai */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="keasramaan-pegawai-form">

  <?php $form = ActiveForm::begin(); ?>

  <div class="row">
    <div class="col-md-6">
      <?= $form->field($model, 'asrama_id')->hiddenInput(['value' => $_GET['id_asrama']])->label(false) ?>
    </div>
  </div>
  
  <div class="row">
      <div class="col-md-6">
        <?= $form->field($model, 'pegawai_id')->widget(Typeahead::classname(),[
        'attribute' => 'pegawai_id',
         'withSubmitButton' => false,
         
         'template' => "<small style='padding:4px'>{{data}}</small>",
         'htmlOptions' => ['class' => 'typeahead', 'placeholder' => 'Nama Pengurus','required'=>true],
         'options' => [
          'hint' => false,
          'highlight' => true,
          'minLength' => 1
         ], 
         'sourceApiBaseUrl' => Url::toRoute(['/askm/keasramaan/list-keasramaan']),
      ])->label(false) ?>
      </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'no_hp')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambahkan' : 'Selesai Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?= Html::a('Batal', Yii::$app->request->referrer, ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>