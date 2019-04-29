<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\Program */

$this->title = 'Update Program: ' . ' ' . $model->kode_program.' '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Program', 'url' => ['jabatan-index']];
$this->params['breadcrumbs'][] = 'Update';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="program-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
        'rencana_strategis' => $rencana_strategis,
        'satuan' => $satuan,
        'struktur_jabatan_has_mata_anggaran' => $struktur_jabatan_has_mata_anggaran,
        'waktu' => $waktu,
        'pelaksana' => $pelaksana,
    ]) ?>

</div>
