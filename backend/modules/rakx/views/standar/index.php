<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rakx\models\search\StandarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Standar';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Standar';
?>
<div class="standar-index">

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'nomor:ntext',
            'name:ntext',
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
                        return Url::toRoute(['standar-view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['standar-edit', 'id' => $key]);
                    }
                    
                  }

                ],
        ],
    ]); 
    Pjax::end() ?>

</div>
