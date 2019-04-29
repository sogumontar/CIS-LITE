<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinKeluar */

$this->title = 'Update Izin';
$this->params['breadcrumbs'][] = ['label' => 'Izin Keluar', 'url' => ['ika-by-mahasiswa-index']];
$this->params['breadcrumbs'][] = ['label' => 'Detail Izin Keluar', 'url' => ['ika-by-mahasiswa-view', 'id' => $model->izin_keluar_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="izin-keluar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
