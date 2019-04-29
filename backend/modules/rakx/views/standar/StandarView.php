<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\Standar */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Standar', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="standar-view">

    <p>
        <?= Html::a('Update', ['standar-edit', 'id' => $model->standar_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nomor:ntext',
            'name:ntext',
            [
                'attribute' => 'desc',
                'format' => 'html',
            ],
        ],
    ]) ?>

</div>
