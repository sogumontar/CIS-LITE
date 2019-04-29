<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\JenisBarang */

$this->title = 'Edit Jenis Barang: ' . ' ' . $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Jenis Barang', 'url' => ['jenis-barang-browse']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['jenis-barang-view', 'id' => $model->jenis_barang_id]];
$this->params['breadcrumbs'][] = 'Edit';
$this->params['header'] = $this->title;
?>
<div class="jenis-barang-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
