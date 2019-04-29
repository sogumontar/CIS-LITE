<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\PoinPelanggaran */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Poin Pelanggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="poin-pelanggaran-view">

    <div class="pull-right">
        <p>
            <?= Html::a('<i class="fa fa-pencil"></i> Edit', ['edit', 'id' => $model->poin_id], ['class' => 'btn btn-default btn-flat btn-set btn-sm']) ?>
        </p>
    </div>

    <h1>Pelanggaran <?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'name',
                'label' => 'Nama',
                'value' => $model->name,
            ],
            [
                'attribute' => 'poin',
                'label' => 'Poin',
                'value' => $model->poin,
            ],

            [
                'attribute' => 'desc',
                'label' => 'Keterangan',
                'value' => $model->desc,
            ],

        ],
    ]) ?>

</div>
