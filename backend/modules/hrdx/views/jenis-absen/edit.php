<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\JenisAbsen */

$this->title = 'Edit Jenis Absen: ' . ' ' . $model->jenis_absen_id;
$this->params['breadcrumbs'][] = ['label' => 'Jenis Absen', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="jenis-absen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
