<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\GolonganKuotaCuti */

$this->title = 'Update Golongan Kuota Cuti: ' . ' ' . $model->golongan_kuota_cuti_id;
$this->params['breadcrumbs'][] = ['label' => 'Golongan Kuota Cuti', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->golongan_kuota_cuti_id, 'url' => ['view', 'id' => $model->golongan_kuota_cuti_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="golongan-kuota-cuti-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
