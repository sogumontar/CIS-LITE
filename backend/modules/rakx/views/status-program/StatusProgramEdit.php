<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\StatusProgram */

$this->title = 'Update Status Program: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Status Program', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['status-program-view', 'id' => $model->status_program_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="status-program-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
