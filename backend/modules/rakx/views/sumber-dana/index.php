<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rakx\models\search\SumberDanaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sumber Dana';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Sumber Dana';
?>
<div class="sumber-dana-index">

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
                        return Url::toRoute(['sumber-dana-view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['sumber-dana-edit', 'id' => $key]);
                    }
                    
                  }

                ],
        ],
    ]); Pjax::end() 
    ?>

</div>
