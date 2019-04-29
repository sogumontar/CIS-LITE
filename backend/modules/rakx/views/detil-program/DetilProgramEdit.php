<?php

use yii\helpers\Html;
use backend\modules\rakx\models\DetilProgram;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\DetilProgram */

$this->title = 'Update Breakdown Program: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Program', 'url' => ['jabatan-index']];
$this->params['breadcrumbs'][] = ['label' => $kode_program.' '.$name, 'url' => ['program/program-view?id='.$program_id.'&tab=data_detil']];
$this->params['breadcrumbs'][] = 'Update';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="detil-program-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <?= $uiHelper->renderContentSubHeader("Perbedaan Total Biaya Breakdown dengan Program: Rp".number_format($jumlah-DetilProgram::getJumlahNumerik(DetilProgram::find()->where('deleted!=1')->andWhere(['program_id' => $program_id])->all(), 'jumlah'),2,',','.')) ?>

    <?= $this->render('_form', [
        'model' => $model,
        'program_id'=>$program_id,
        'kode_program'=>$kode_program,
        'name'=>$name,
        'jumlah'=>$jumlah,
    ]) ?>

</div>
