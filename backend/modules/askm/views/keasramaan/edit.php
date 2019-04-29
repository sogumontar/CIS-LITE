<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\KeasramaanPegawai */

$this->title = 'Edit Data Diri Keasramaan';
$this->params['breadcrumbs'][] = ['label' => 'Data Diri Keasramaan', 'url' => ['view', 'id' => $model->keasramaan_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="keasramaan-pegawai-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
