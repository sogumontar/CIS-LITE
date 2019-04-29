<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\StrukturJabatanHasMataAnggaran */

$this->title = 'Update Penugasan Anggaran: ' . ' ' . $model->strukturJabatan->jabatan.' - '.$mo;
$this->params['breadcrumbs'][] = ['label' => 'Penugasan Anggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->struktur_jabatan_has_mata_anggaran_id, 'url' => ['struktur-jabatan-has-mata-anggaran-view', 'id' => $model->struktur_jabatan_has_mata_anggaran_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="struktur-jabatan-has-mata-anggaran-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'struktur_jabatan' => $struktur_jabatan,
        'mata_anggaran' => $mata_anggaran,
        'tahun_anggaran' => $tahun_anggaran,
    ]) ?>

</div>
