<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\widgets\Typeahead;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\DimPenilaian */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dim-penilaian-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'desc')->textArea(['rows' => 4]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambah' : 'Edit', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
