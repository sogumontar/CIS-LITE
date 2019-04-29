<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rakx\models\search\TahunAnggaranSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tahun Anggaran';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tahun-anggaran-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'tahun',
            'desc:ntext',
            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {edit}',
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['tahun-anggaran-view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['tahun-anggaran-edit', 'id' => $key]);
                    }
                    
                  }

                ],
        ],
    ]); ?>

</div>
