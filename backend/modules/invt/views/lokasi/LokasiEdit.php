<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\invt\models\Lokasi */

$this->title = 'Edit Lokasi: ' . ' ' . $model->nama_lokasi;
$this->params['breadcrumbs'][] = ['label' => 'Lokasi', 'url' => ['lokasi-browse']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_lokasi, 'url' => ['lokasi-view', 'lokasi_id' => $model->lokasi_id]];
$this->params['breadcrumbs'][] = 'Edit';
$this->params['header']=$this->title;
?>
<div class="lokasi-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
