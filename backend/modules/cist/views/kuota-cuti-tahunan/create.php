<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\KuotaCutiTahunan */

$this->title = 'Generate Kuota Cuti Tahunan';
$this->params['breadcrumbs'][] = ['label' => 'Kuota Cuti Tahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kuota-cuti-tahunan-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <hr>

    <div class="callout callout-info">
      <?php
        echo "<b>Keterangan</b><br/>";
        echo 'Jumlah libur cuti bersama nasional mengurangi jumlah kuota cuti tahunan';
      ?>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'modelPegawai' => $modelPegawai,
    ]) ?>

</div>
