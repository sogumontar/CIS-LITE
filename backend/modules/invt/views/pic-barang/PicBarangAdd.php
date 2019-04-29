<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\PicBarang */

$this->title = 'Assign PIC Barang : '.$modelPengeluaran->kode_inventori;
$this->params['breadcrumbs'][] = ['label' => 'List Distribusi', 'url' => ['list-distribusi','unit_id'=>$_GET['unit_id']]];
$this->params['breadcrumbs'][] = ['label' => 'Daftar PIC Barang', 'url' => ['pic-barang-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="pic-barang-create">

    <?= $this->render('_form', [
                'model' => $model,
                'modelPengeluaran'=>$modelPengeluaran,
                'modelPegawai'=>$modelPegawai,
    ]) ?>

</div>
