<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\SwitchColumn;
use common\components\ToolsColumn;
use common\helpers\LinkHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rakx\models\search\RencanaStrategisSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rencana Strategis';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Rencana Strategis';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="rencana-strategis-index">

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nomor',
            'name',
            [
                'attribute' => 'desc',
                'format' => 'html',
                'value' => function($model){
                    return substr($model->desc,0,200).(strlen($model->desc)>200?'...':'');
                }
            ],
            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {edit}',
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['rencana-strategis-view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['rencana-strategis-edit', 'id' => $key]);
                    }
                    
                  }

                ],
        ],
    ]);
    Pjax::end() ?>

</div>
