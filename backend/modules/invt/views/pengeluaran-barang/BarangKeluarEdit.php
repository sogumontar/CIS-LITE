<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\PengeluaranBarang */

$this->title = 'Edit Pengeluaran Barang: ' . ' ' . $model->barang->nama_barang;
$this->params['breadcrumbs'][] = ['label' => 'Daftar Pengeluaran Barang', 'url' => ['barang-keluar-browse']];
$this->params['breadcrumbs'][] = ['label'=> 'Detail Barang Keluar','url'=>['detail-barang-keluar','keterangan_pengeluaran_id'=>$model->keteranganPengeluaran->keterangan_pengeluaran_id]];
$this->params['breadcrumbs'][] = 'Edit';
$this->params['header'] = $this->title
?>
<div class="pengeluaran-barang-update">

    <?= $this->render('_form', [
        'model' => $model,
        '_lokasi'=>$_lokasi,
    ]) ?>

</div>
