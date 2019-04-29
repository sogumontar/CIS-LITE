<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiNontahunan */

$this->title = 'Form Permohonan Cuti Nontahunan';
$this->params['breadcrumbs'][] = ['label' => 'Permohonan Cuti Nontahunan', 'url' => ['index-by-staf']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="permohonan-cuti-nontahunan-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= $this->render('_formByStaf', [
        'model' => $model,
        'namaPegawai' => $namaPegawai,
    ]) ?>

</div>
