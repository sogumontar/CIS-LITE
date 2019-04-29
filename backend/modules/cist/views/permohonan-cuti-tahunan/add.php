<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiTahunan */

$this->title = 'Create Permohonan Cuti Tahunan';
$this->params['breadcrumbs'][] = ['label' => 'Permohonan Cuti Tahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permohonan-cuti-tahunan-create">

    <h1><?= Html::encode($this->title) ?></h1>
     
    <?= $this->render('_form', [
        'model' => $model,
        'kuota' => $kuota,
    ]) ?>

</div>
