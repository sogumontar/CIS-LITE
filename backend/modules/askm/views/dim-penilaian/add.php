<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\DimPenilaian */

$this->title = 'Tambah Pelanggaran';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Perilaku Mahasiswa/i', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dim['nama'], 'url' => ['view', 'id' => $model->penilaian_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dim-penilaian-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
