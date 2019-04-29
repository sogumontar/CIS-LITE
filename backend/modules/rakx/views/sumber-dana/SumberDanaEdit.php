<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\SumberDana */

$this->title = 'Update Sumber Dana: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sumber Dana', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['sumber-dana-view', 'id' => $model->sumber_dana_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sumber-dana-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
