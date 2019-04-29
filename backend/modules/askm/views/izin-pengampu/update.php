<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinPengampu */

$this->title = 'Update Izin Pengampu: ' . ' ' . $model->id_izin_pengampu;
$this->params['breadcrumbs'][] = ['label' => 'Izin Pengampus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_izin_pengampu, 'url' => ['view', 'id' => $model->id_izin_pengampu]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="izin-pengampu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
