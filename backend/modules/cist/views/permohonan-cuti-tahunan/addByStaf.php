<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiTahunan */

$this->title = 'Form Permohonan Cuti Tahunan';
$this->params['breadcrumbs'][] = ['label' => 'Permohonan Cuti Tahunan', 'url' => ['index-by-staf']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="permohonan-cuti-tahunan-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= $this->render('_formByStaf', [
        'model' => $model,
        'namaPegawai' => $namaPegawai,
        'sisa_kuota' => $sisa_kuota,
    ]) ?>

</div>
