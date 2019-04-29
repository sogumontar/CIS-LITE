<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\ProgramHasWaktu */

$this->title = 'Update Program Has Waktu: ' . ' ' . $model->program_has_waktu_id;
$this->params['breadcrumbs'][] = ['label' => 'Program Has Waktus', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->program_has_waktu_id, 'url' => ['view', 'id' => $model->program_has_waktu_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="program-has-waktu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
