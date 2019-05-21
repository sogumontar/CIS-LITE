<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rppx\models\PenugasanPengajaran */

$this->title = 'Update Penugasan Pengajaran: ' . ' ' . $model->penugasan_pengajaran_id;
$this->params['breadcrumbs'][] = ['label' => 'Penugasan Pengajarans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->penugasan_pengajaran_id, 'url' => ['view', 'id' => $model->penugasan_pengajaran_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="penugasan-pengajaran-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
