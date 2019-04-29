<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\PegawaiAbsensi */

$this->title = 'Edit '.$model->jenisAbsen->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Absensi', 'url' => ['browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = "Edit Formulir Pengajuan ".$model->jenisAbsen->nama;

?>
<div class="pegawai-absensi-update">

    <?= $this->render('_form', [
        'model' => $model,
        'penerima_tugas' => $penerima_tugas,
    ]) ?>

</div>
