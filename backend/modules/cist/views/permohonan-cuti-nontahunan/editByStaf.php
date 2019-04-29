<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiNontahunan */

$this->title = 'Update Permohonan Cuti Nontahunan: ' . ' ' . $model->kategori->name;
$this->params['breadcrumbs'][] = ['label' => 'Permohonan Cuti Nontahunan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kategori->name, 'url' => ['view', 'id' => $model->permohonan_cuti_nontahunan_id]];
$this->params['breadcrumbs'][] = 'Update';
$this->params['header'] = $this->title;
?>
<div class="permohonan-cuti-nontahunan-update">

    <?= $this->render('_formByStaf', [
        'model' => $model,
        'namaPegawai' => $namaPegawai,
    ]) ?>

</div>
