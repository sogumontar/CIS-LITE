<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\Redactor;
use yii\helpers\ArrayHelper;
use backend\modules\askm\models\DimPelanggaran;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\PoinKebaikan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="poin-kebaikan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textArea(['rows' => 4]) ?>

    <br>

    <div class="callout callout-info">
      <?php
        echo "<b>Jika ingin menebus pelanggaran, silahkan pilih pelanggaran yang ingin ditebus di bawah ini. Jika tidak, anda bisa mengabaikannya</b>";
      ?>
    </div>

    <div class="row">
        <?php
        if ($model->pelanggaran_id == NULL) { ?>
        <div class="col-md-6 col-sm-6">
            <?= $form->field($model, 'pelanggaran_id')->dropDownList(ArrayHelper::map(DimPelanggaran::find()->where('deleted!=1')->andWhere(['penilaian_id' => $_GET['penilaian_id']])->all(), 'pelanggaran_id', 'poin.name'), ['prompt'=>'Belum Dipilih'])?>
        </div>
        <?php
        } else if ($model->pelanggaran_id != NULL) { ?>

        <div class="col-md-6 col-sm-6">
            <?= $form->field($model, 'pelanggaran_id')->dropDownList(ArrayHelper::map(DimPelanggaran::find()->where('deleted!=1')->andWhere(['penilaian_id' => $_GET['penilaian_id']])->all(), 'pelanggaran_id', 'poin.name'), ['prompt'=>'Belum Dipilih', 'disabled' => !$model->isNewRecord,])?>
        </div>

        <?php } ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
