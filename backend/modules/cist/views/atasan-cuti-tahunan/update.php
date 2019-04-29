<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\AtasanCutiTahunan */

$this->title = 'Update Atasan Cuti Tahunan: ' . ' ' . $model->atasan_cuti_id;
$this->params['breadcrumbs'][] = ['label' => 'Atasan Cuti Tahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->atasan_cuti_id, 'url' => ['view', 'id' => $model->atasan_cuti_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="atasan-cuti-tahunan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
