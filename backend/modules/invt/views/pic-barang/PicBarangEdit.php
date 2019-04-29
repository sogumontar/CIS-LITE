<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\PicBarang */

$this->title = 'Edit Pic Barang: ' . ' ' . $modelPengeluaran->kode_inventori;
$this->params['breadcrumbs'][] = ['label' => 'Daftar PIC Barang', 'url' => ['pic-barang-browse']];
$this->params['breadcrumbs'][] = ['label' => $modelPengeluaran->kode_inventori, 'url' => ['pic-barang-view', 'id' => $model->pic_barang_id]];
$this->params['header'] = $this->title;
?>
<div class="pic-barang-update">

    <?= $this->render('_form', [
                'model' => $model,
                'modelPengeluaran'=>$modelPengeluaran,
                'modelPegawai'=>$modelPegawai,
                'fileList'=>$fileList,
    ]) ?>

</div>
