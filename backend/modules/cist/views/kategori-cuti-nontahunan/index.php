<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\cist\models\search\KategoriCutiNontahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kategori Cuti Nontahunan';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="kategori-cuti-nontahunan-index">

    <div class="pull-right">
        <p>
            <?= Html::a('<i class="fa fa-plus-square"></i> Tambah', ['add'], ['class' => 'btn btn-default btn-flat btn-set btn-sm']) ?>
        </p>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['style' => 'font-size:12px;'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'lama_pelaksanaan',
            [
                'attribute' => 'satuan',
                'filter' => '',
                'value' => function($data){
                    return $data->satuan==2?'Bulan':'Hari';
                }
            ],

            ['class' => 'common\components\ToolsColumn',
                'template' => '{edit}',
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
