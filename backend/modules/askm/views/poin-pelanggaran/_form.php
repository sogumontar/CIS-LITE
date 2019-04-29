<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\widgets\Redactor;
use backend\modules\askm\models\TingkatPelanggaran;
use backend\modules\askm\models\BentukPelanggaran;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\PoinPelanggaran */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="poin-pelanggaran-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-1">
            <?= $form->field($model, 'poin')->textInput() ?>
        </div>
    </div>

    <?= $form->field($model, 'desc')->textArea(['rows' => 4]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'bentuk_id')->dropDownList(ArrayHelper::map(BentukPelanggaran::find()->where('deleted!=1')->all(), 'bentuk_id', 'name'), ['prompt'=>'Bentuk Pelanggaran'])?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'tingkat_id')->dropDownList(ArrayHelper::map(TingkatPelanggaran::find()->where('deleted!=1')->all(), 'tingkat_id', 'name'), ['prompt'=>'Tingkat Pelanggaran'])?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
