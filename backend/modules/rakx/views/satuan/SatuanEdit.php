<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\Satuan */

$this->title = 'Update Satuan: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Satuan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['satuan-view', 'id' => $model->satuan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="satuan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
