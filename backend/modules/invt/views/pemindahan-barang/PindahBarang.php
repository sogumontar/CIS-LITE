<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\PemindahanBarang */

$this->title = 'Tambah Pemindahan Barang';
$this->params['breadcrumbs'][] = ['label' => 'Histori Pemindahan Barang', 'url' => ['pindah-barang-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="pemindahan-barang-create">

    <?= $this->render('_form', [
        'dataProvider' => $dataProvider,
        '_lokasi'=>$_lokasi,
        '_tanggalPindah'=>$_tanggalPindah,
    ]) ?>

</div>
