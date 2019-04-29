<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\Program */

$this->title = 'Create Program';
$this->params['breadcrumbs'][] = ['label' => 'Usul Program', 'url' => ['usulan-index']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="program-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <?= $this->render('_formUsulan', [
        'model' => $model,
        'rencana_strategis' => $rencana_strategis,
        'satuan' => $satuan,
        'struktur_jabatan_has_mata_anggaran' => $struktur_jabatan_has_mata_anggaran,
        'waktu' => $waktu,
        'pengusul' => $pengusul,
    ]) ?>    

</div>