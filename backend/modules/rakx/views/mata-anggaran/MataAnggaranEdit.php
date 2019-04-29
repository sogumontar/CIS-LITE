<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\MataAnggaran */

$this->title = 'Update Mata Anggaran: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Mata Anggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['mata-anggaran-view', 'id' => $model->mata_anggaran_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mata-anggaran-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'standar' => $standar,
    ]) ?>

</div>
