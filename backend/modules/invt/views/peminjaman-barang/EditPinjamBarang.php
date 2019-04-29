<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\PeminjamanBarang */

$this->title = 'Edit: Pengajuan Peminjaman Barang';
$this->params['breadcrumbs'][] = ['label' => 'Peminjaman Barang', 'url' => ['pinjam-barang-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="peminjaman-barang-create">
    <?= $this->render('_form', [
    	'model'=>$model,
    	'unit'=>$unit,
    ]) ?>

</div>
