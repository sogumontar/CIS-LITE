<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\SuratTugas */

$this->title = 'Update Surat Tugas: ' . ' ' . $model->surat_tugas_id;
$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas', 'url' => ['browse']];
$this->params['breadcrumbs'][] = ['label' => $model->surat_tugas_id, 'url' => ['detail', 'id' => $model->surat_tugas_id]];
$this->params['breadcrumbs'][] = 'Update';
$this->params['header'] = 'Ubah Surat Tugas';
?>
<div class="surat-tugas-update">

    <?= $this->render('_form', [
        'model' => $model,
        'pemberi_tugas' => $pemberi_tugas,
        'penerima_tugas_1' => $penerima_tugas_1,
        'penerima_tugas_2' => $penerima_tugas_2,
    ]) ?>

</div>
