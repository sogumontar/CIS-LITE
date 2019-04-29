<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\inst\models\StrukturJabatan */

$this->title = 'Update Struktur Jabatan: ' . ' ' . $model->jabatan;
if($otherRenderer){
    $this->params['breadcrumbs'][] = ['label' => 'Instansi Manager', 'url' => ['inst-manager/index']];
    $this->params['breadcrumbs'][] = ['label' => $instansi_name, 'url' => ['inst-manager/strukturs?instansi_id='.$model->instansi_id]];
}else
    $this->params['breadcrumbs'][] = ['label' => 'Struktur Jabatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->jabatan, 'url' => ['pejabat/pejabat-by-jabatan-view', 'jabatan_id' => $model->struktur_jabatan_id, 'otherRenderer' => $otherRenderer]];
$this->params['breadcrumbs'][] = 'Update';

$uiHelper = \Yii::$app->uiHelper;
?>
<div class="struktur-jabatan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model, 
        'parent' => $parent,
        'instansi' => $instansi,
        'unit' => $unit,
        'tenant' => $tenant,
        'mata_anggaran' => $mata_anggaran,
        'laporan' => $laporan,
    ]) ?>

</div>
