<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rakx\models\search\SatuanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Satuan';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Satuan';
?>
<div class="satuan-index">

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                    'attribute' => 'desc',
                    'format' => 'html',
                ],
            ['class' => 'common\components\ToolsColumn',
                'template' => '{edit}',
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['satuan-view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['satuan-edit', 'id' => $key]);
                    }
                    
                  }

                ],
        ],
    ]);
    Pjax::end() ?>

</div>
