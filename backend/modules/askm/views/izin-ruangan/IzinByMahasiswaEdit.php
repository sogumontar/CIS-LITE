<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinPenggunaanRuangan */

$this->title = 'Edit Izin';
$this->params['breadcrumbs'][] = ['label' => 'Izin Penggunaan Ruangan', 'url' => ['izin-by-mahasiswa-index']];
$this->params['breadcrumbs'][] = ['label' => 'Detail Izin', 'url' => ['izin-by-mahasiswa-view', 'id' => $model->izin_ruangan_id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="izin-ruangan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formByMahasiswa', [
        'model' => $model,
    ]) ?>

</div>
