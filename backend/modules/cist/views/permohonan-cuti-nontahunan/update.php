<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiNontahunan */

$this->title = 'Update Permohonan Cuti Nontahunan: ' . ' ' . $model->pmhnn_cuti_nthn_id;
$this->params['breadcrumbs'][] = ['label' => 'Permohonan Cuti Nontahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pmhnn_cuti_nthn_id, 'url' => ['view', 'id' => $model->pmhnn_cuti_nthn_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="permohonan-cuti-nontahunan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
