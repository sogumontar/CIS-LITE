<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\PeminjamanBarang */

$this->title = 'Pilih Barang';
$this->params['breadcrumbs'][] = ['label' => 'Peminjaman Barang', 'url' => ['pinjam-barang-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="detail-peminjaman-barang-create">
    <?= $this->render('_formDetailPinjamBarang', [
        'dataProvider'=>$dataProvider,
        'searchModel'=>$searchModel,
        '_jenis'=>$_jenis,
        '_kategori'=>$_kategori,
        'modelPengajuan'=>$modelPengajuan,
    ]) ?>

</div>
