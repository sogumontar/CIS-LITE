<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\Pejabat */

$this->title = 'Update Pejabat: ' .$model->pegawai['nama'].' - '.$model->strukturJabatan['jabatan'];
$this->params['breadcrumbs'][] = ['label' => 'Management Pejabat', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pegawai['nama'].' - '.$model->strukturJabatan['jabatan'], 'url' => ['pejabat-view', 'id' => $model->pejabat_id]];
$this->params['breadcrumbs'][] = 'Update';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pejabat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <?= $uiHelper->beginContentBlock(['id' => 'pejabat-edit',
            // 'width' => 4,
            'height' => 12,
        ]) ?>
        <div class="callout callout-info">
        <?php
            echo 'Periode terdiri dari 3, yaitu :<br />';
            echo '1. Periode Lampau, Awal & Akhir Masa Kerja berlangsung sebelum hari ini<br />';
            echo '2. Periode Sekarang, Awal Masa Kerja berlangsung sebelum hari ini dan Akhir Masa Kerja berlangsung setelah hari ini<br />';
            echo '3. Periode Yang Akan Datang, Awal & Akhir Masa Kerja berlangsung setelah hari ini<br />';
            echo '<br /><b>Dalam mengedit Masa Kerja Pejabat, Masa Kerja Baru harus berada dalam 1 Periode yang sama dengan Masa Kerja Lama.</b>';
        ?>
        </div>
    <?=$uiHelper->endContentBlock()?>

    <?= $this->render('_form', [
        'model' => $model,
        'struktur_jabatan' => $struktur_jabatan,
        'pegawai' => $pegawai,
    ]) ?>

</div>
