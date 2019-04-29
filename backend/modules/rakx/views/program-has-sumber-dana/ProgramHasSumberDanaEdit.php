<?php

use yii\helpers\Html;
use backend\modules\rakx\models\ProgramHasSumberDana;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\ProgramHasSumberDana */

$this->title = 'Update Sumber Dana: ' . ' ' . $model->sumberDana->name;
$this->params['breadcrumbs'][] = ['label' => 'Program', 'url' => ['jabatan-index']];
$this->params['breadcrumbs'][] = ['label' => (strlen($kode_program.' '.$name)>100?(substr($kode_program.' '.$name,0,100).'...'):$kode_program.' '.$name), 'url' => ['program/program-view?id='.$program_id.'&tab=data_dana']];
$this->params['breadcrumbs'][] = 'Update';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="program-has-sumber-dana-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <?= $uiHelper->renderContentSubHeader("Perbedaan Total Sumber Dana dengan Biaya Program: Rp".number_format($jumlah-ProgramHasSumberDana::getJumlahNumerik(ProgramHasSumberDana::find()->where('deleted!=1')->andWhere(['program_id' => $program_id])->all(), 'jumlah'),2,',','.')); ?>

    <?= $this->render('_formEdit', [
        'model' => $model,
        'sumber_dana' => $sumber_dana,
        'program_id'=>$program_id,
        'kode_program'=>$kode_program,
        'name'=>$name,
        'jumlah'=>$jumlah,
    ]) ?>

</div>
