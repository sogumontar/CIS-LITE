<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\PeminjamanBarang */

$this->title = 'Update Peminjaman Barang: ' . ' ' . $model->peminjaman_barang_id;
$this->params['breadcrumbs'][] = ['label' => 'Peminjaman Barangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->peminjaman_barang_id, 'url' => ['view', 'id' => $model->peminjaman_barang_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="peminjaman-barang-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
