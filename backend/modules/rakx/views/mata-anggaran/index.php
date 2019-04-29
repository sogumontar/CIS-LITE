<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\components\ToolsColumn;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rakx\models\search\MataAnggaranSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mata Anggaran';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Mata Anggaran';
?>
<div class="mata-anggaran-index">

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            /*[
                'attribute' => 'standar_name',
                'value' => function($model){return 'Standar '.$model->standar->nomor.': '.$model->standar->name; },
                'label' => 'Standar',
                'filter' => ArrayHelper::map($standar, 'standar_id', function($model){return 'Standar '.$model->nomor.': '.$model->name; }),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
            ],*/
            'kode_anggaran',
            'name',
            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {edit}',
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['mata-anggaran-view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['mata-anggaran-edit', 'id' => $key]);
                    }
                }
            ],
        ],
    ]);
    Pjax::end() ?>

</div>
