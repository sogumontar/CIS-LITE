<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\DimPelanggaran */

$this->title = 'Edit Pelanggaran';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Perilaku Mahasiswa/i', 'url' => ['dim-penilaian/index']];
$this->params['breadcrumbs'][] = ['label' => $dim, 'url' => ['dim-penilaian/view', 'id' => $_GET['penilaian_id']]];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="dim-pelanggaran-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
