<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiTahunan */

$this->title = 'Update Permohonan Cuti Tahunan: ' . ' ' . $model->pmhnn_cuti_thn_id;
$this->params['breadcrumbs'][] = ['label' => 'Permohonan Cuti Tahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pmhnn_cuti_thn_id, 'url' => ['view', 'id' => $model->pmhnn_cuti_thn_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="permohonan-cuti-tahunan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
