<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\RencanaStrategis */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Rencana Strategis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="rencana-strategis-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=$uiHelper->renderLine(); ?>

    <p>
        <?= Html::a('Update', ['rencana-strategis-edit', 'id' => $model->rencana_strategis_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nomor',
            'name:ntext',
            [
                'attribute' => 'desc',
                'format' => 'html',
            ],
        ],
    ]) ?>

</div>
