<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\DimPenilaian */

$this->title = 'Edit Pelanggaran';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Perilaku Mahasiswa/i', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dim['nama'], 'url' => ['view', 'id' => $model->penilaian_id]];
$this->params['breadcrumbs'][] = 'Edit';
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="dim-penilaian-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
