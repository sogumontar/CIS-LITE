<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\TahunAnggaran */

$this->title = 'Update Tahun Anggaran: ' . ' ' . $model->tahun;
$this->params['breadcrumbs'][] = ['label' => 'Tahun Anggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->tahun, 'url' => ['tahun-anggaran-view', 'id' => $model->tahun_anggaran_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tahun-anggaran-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
