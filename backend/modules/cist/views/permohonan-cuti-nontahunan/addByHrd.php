<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiNontahunan */

$this->title = 'Form Permohonan Cuti Nontahunan';
$this->params['breadcrumbs'][] = ['label' => 'Permohonan Cuti Nontahunan', 'url' => ['index-by-hrd']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="permohonan-cuti-nontahunan-create">

    <?= $this->render('_formByHrd', [
        'model' => $model,
        'namaPegawai' => $namaPegawai,
    ]) ?>

</div>
