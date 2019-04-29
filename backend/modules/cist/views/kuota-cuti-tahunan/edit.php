<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\KuotaCutiTahunan */

$this->title = 'Edit Kuota Cuti Tahunan: ' . ' ' . $model->pegawai->nama;
$this->params['breadcrumbs'][] = ['label' => 'Kuota Cuti Tahunan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pegawai->nama, 'url' => ['view', 'id' => $model->kuota_cuti_tahunan_id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="kuota-cuti-tahunan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formEdit', [
        'model' => $model,
    ]) ?>

</div>
