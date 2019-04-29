<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\search\BarangSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="barang-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'barang_id') ?>

    <?= $form->field($model, 'kode_barang') ?>

    <?= $form->field($model, 'nama_barang') ?>

    <?= $form->field($model, 'jenis_barang_id') ?>

    <?= $form->field($model, 'kategori_id') ?>

    <?php // echo $form->field($model, 'lokasi_id') ?>

    <?php // echo $form->field($model, 'jumlah') ?>

    <?php // echo $form->field($model, 'supplier') ?>

    <?php // echo $form->field($model, 'harga') ?>

    <?php // echo $form->field($model, 'tanggal_masuk') ?>

    <?php // echo $form->field($model, 'satuan_id') ?>

    <?php // echo $form->field($model, 'desc') ?>

    <?php // echo $form->field($model, 'kapasitas') ?>

    <?php // echo $form->field($model, 'nama_file') ?>

    <?php // echo $form->field($model, 'kode_file') ?>

    <?php // echo $form->field($model, 'jumlah_rusak') ?>

    <?php // echo $form->field($model, 'status_barang') ?>

    <?php // echo $form->field($model, 'unit_inventori_id') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
