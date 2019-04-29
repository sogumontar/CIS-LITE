<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\widgets\Typeahead;
use backend\modules\askm\models\Kamar;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\DimKamar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dim-kamar-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'kamar_id')->dropDownList(ArrayHelper::map(Kamar::find()->where('deleted!=1')->andWhere(['asrama_id' => $_GET['id_asrama']])->all(), 'kamar_id', 'nomor_kamar'), ['prompt'=>'Pilih Kamar', 'required' => true])?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Tambahkan' : 'Pindahkan', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

        <?php // echo Html::a('Batal', ['/askm/kamar/view', 'id' => $_GET['id']], ['class' => 'btn btn-danger']) ?>

        <?= Html::a('Batal', ['/askm/asrama/view-detail-asrama', 'id' => $_GET['id_asrama']], ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
