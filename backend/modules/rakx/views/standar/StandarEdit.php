<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\Standar */

$this->title = 'Update Standar: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Standar', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['standar-view', 'id' => $model->standar_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="standar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
