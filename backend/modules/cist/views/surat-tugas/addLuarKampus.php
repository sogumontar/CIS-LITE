<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\SuratTugas */

$this->title = 'Permohonan Surat Tugas Perjalanan Dinas';
$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas', 'url' => ['index-pegawai']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="surat-tugas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formLuarKampus', [
        'model' => $model,
        'modelAtasan' => $modelAtasan,
        'pegawai' => $pegawai,
    ]) ?>

</div>
