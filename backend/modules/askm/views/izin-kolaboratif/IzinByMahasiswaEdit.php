<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinTambahanJamKolaboratif */

$this->title = 'Update Izin Tambahan Jam Kolaboratif';
$this->params['breadcrumbs'][] = ['label' => 'Izin Tambahan Jam Kolaboratif', 'url' => ['izin-by-mahasiswa-index']];
$this->params['breadcrumbs'][] = ['label' => 'Detail View', 'url' => ['izin-by-mahasiswa-view', 'id' => $model->izin_kolaboratif_id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="izin-kolaboratif-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formByMahasiswa', [
        'model' => $model,
    ]) ?>

</div>
