<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Barang */

$this->title = 'Tambah Barang';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Inventori', 'url' => ['barang-browse-byadmin']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="barang-create">
    <?= $this->render('_form', [
        'model' => $model,
        'model_satuan'=>$model_satuan,
        'model_kategori_barang'=>$model_kategori_barang,
        'model_jenis_barang'=>$model_jenis_barang,
        'model_brand'=>$model_brand,
        'model_vendor'=>$model_vendor,
    ]) ?>

</div>
