<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\SuratTugas */

$this->title = 'Penugasan Surat Tugas Perjalanan Dinas';
$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas Bawahan', 'url' => ['index-surat-bawahan']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="surat-tugas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formPenugasanLuarKampus', [
        'model' => $model,
        'pegawai' => $pegawai,
    ]) ?>

</div>
