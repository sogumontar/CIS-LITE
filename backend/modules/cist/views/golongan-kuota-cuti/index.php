<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\cist\models\search\GolonganKuotaCutiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Golongan Kuota Cuti';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="golongan-kuota-cuti-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Golongan Kuota Cuti', ['add'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['style' => 'font-size:12px;'],
        'filterModel' => $searchModel,
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '-',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'nama_golongan',
            'min_tahun_kerja',
            'max_tahun_kerja',
            'kuota',

            ['class' => 'common\components\ToolsColumn',
                'template' => '{view}',
                'header' => 'Action',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['view', 'id' => $key]);
                    }
                }

            ],
        ],
    ]); ?>

</div>
