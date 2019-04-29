<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\SuratTugas */

$this->title = 'Ubah Surat Tugas: ' . ' ' . $model->agenda;
$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas', 'url' => ['index-pegawai']];
$this->params['breadcrumbs'][] = ['label' => $model->agenda, 'url' => ['view-pegawai', 'id' => $model->surat_tugas_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="surat-tugas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formUpdateLuarKampus', [
        'model' => $model,
        'modelAtasan' => $modelAtasan,
        'modelAssigned' => $modelAssigned,
        'modelSisaAtasan' => $modelSisaAtasan,
        'modelAssignee' => $modelAssignee,
        'modelLampiran' => $modelLampiran,
    ]) ?>

</div>
