<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\PoinPelanggaran */

$this->title = 'Edit Poin Pelanggaran: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Poin Pelanggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->poin_id]];
$this->params['breadcrumbs'][] = 'Edit';
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="poin-pelanggaran-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
