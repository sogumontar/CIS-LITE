<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\RencanaStrategis */

$this->title = 'Update Rencana Strategis: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Rencana Strategis', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['rencana-strategis-view', 'id' => $model->rencana_strategis_id]];
$this->params['breadcrumbs'][] = 'Update';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="rencana-strategis-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
