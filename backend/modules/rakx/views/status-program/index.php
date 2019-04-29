<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rakx\models\search\StatusProgramSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Status Program';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Status Program';
?>
<div class="status-program-index">

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'desc:ntext',
            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {edit}',
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['status-program-view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['status-program-edit', 'id' => $key]);
                    }
                    
                  }

                ],
        ],
    ]); 
    Pjax::end() ?>

</div>
