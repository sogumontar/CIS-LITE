<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiTahunan */

$this->title = 'Form Permohonan Cuti Tahunan';
$this->params['breadcrumbs'][] = ['label' => 'Permohonan Cuti Tahunan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="permohonan-cuti-tahunan-create">
     
    <?= $this->render('_formByAtasan', [
        'model' => $model,
        'namaPegawai' => $namaPegawai,
        'kuota' => $kuota,
    ]) ?>

</div>
