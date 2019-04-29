<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\MataAnggaran */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Mata Anggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="mata-anggaran-view">

    <p>
        <?= Html::a('Update', ['mata-anggaran-edit', 'id' => $model->mata_anggaran_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            /*[
                'attribute' => 'standar_id',
                'value' => function($model){return 'Standar '.$model->standar->nomor.': '.$model->standar->name; },
                'label' => 'Standar',
            ],*/
            'kode_anggaran',
            'name',
            [
                'attribute' => 'desc',
                'format' => 'html',
            ],
        ],
    ]) ?>

</div>
