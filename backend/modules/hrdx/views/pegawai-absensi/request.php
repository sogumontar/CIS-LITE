<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\PegawaiAbsensi */


$this->title = 'Request '.$jenis_absensi->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Absensi', 'url' => ['browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = "Formulir Pengajuan ".$jenis_absensi->nama;
?>

<div class="absensi-browse">

    <?= $this->render('_form', [
        'model' => $model,
        'jenis_absensi' => $jenis_absensi,
        'penerima_tugas' => $penerima_tugas,
    ]) ?>

</div>
