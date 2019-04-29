<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\helpers\LinkHelper;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
use backend\modules\askm\models\TingkatPelanggaran;
use backend\modules\askm\models\BentukPelanggaran;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\PoinPelanggarannSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Poin Pelanggaran';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="poin-pelanggaran-index">

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
            'poin',
            [
                'attribute'=>'tingkat_id',
                'label' => 'Tingkat',
                'format' => 'raw',
                'filter'=>ArrayHelper::map(TingkatPelanggaran::find()->andWhere('deleted!=1')->asArray()->all(), 'tingkat_id', 'name'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'value' => 'tingkat.name',
            ],
            [
                'attribute'=>'bentuk_id',
                'label' => 'Bentuk Pelanggaran',
                'format' => 'raw',
                'filter'=>ArrayHelper::map(BentukPelanggaran::find()->andWhere('deleted!=1')->asArray()->all(), 'bentuk_id', 'name'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'value' => 'bentuk.name',
            ],

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
