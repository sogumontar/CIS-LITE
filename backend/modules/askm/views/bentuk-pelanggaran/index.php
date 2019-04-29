<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\LinkHelper;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\BentukPelanggaranSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bentuk Pelanggaran';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="bentuk-pelanggaran-index">

    <div class="pull-right">
        <p>
            <?= Html::a('<i class="fa fa-plus-square"></i> Tambah', ['add'], ['class' => 'btn btn-default btn-flat btn-set btn-sm']) ?>
        </p>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'desc:ntext',

            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {edit}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    },
                    'edit' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'Edit', 'fa fa-pencil');
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['edit', 'id' => $key]);
                    }

                }
            ],
        ],
    ]); ?>

</div>
