<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\helpers\LinkHelper;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\DimPenilaianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Perilaku Mahasiswa/i';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="dim-penilaian-index">

    <div class="pull-right">
        <?= Html::a('<i class="fa fa-refresh"></i> Generate New', ['generate']) ?>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= $uiHelper->beginContentRow(); ?>
    <?= $this->render('_search', ['searchModel' => $searchModel, 'prodi' => $prodi, 'dosen_wali' => $dosen_wali, 'asrama' => $asrama, 'ta' => $ta, 'sem_ta' => $sem_ta, 'angkatan' => $angkatan]); ?>
    <?= $uiHelper->endContentRow(); ?>
    <?=$uiHelper->renderLine(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'rowOptions' => function($model){
            if($model->akumulasi_skor == 0){
                return ['class' => 'bg-green'];
            } else if($model->akumulasi_skor >= 1 && $model->akumulasi_skor <= 5){
                return ['class' => 'bg-teal'];
            } else if($model->akumulasi_skor >= 6 && $model->akumulasi_skor <= 10){
                return ['class' => 'bg-light-blue'];
            } else if($model->akumulasi_skor >= 11 && $model->akumulasi_skor <= 15){
                return ['class' => 'bg-blue'];
            } else if($model->akumulasi_skor >= 16 && $model->akumulasi_skor <= 20){
                return ['class' => 'bg-yellow'];
            } else if($model->akumulasi_skor >= 21 && $model->akumulasi_skor <= 25){
                return ['class' => 'bg-orange'];
            } else if($model->akumulasi_skor > 25){
                return ['class' => 'bg-red'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'dim_nama',
                'label'=>'Nama Mahasiswa',
                'format' => 'raw',
                'value'=>function ($model) {
                    return "<a href='".Url::toRoute(['/dimx/dim/mahasiswa-view', 'dim_id' => $model->dim_id])."' style='color: #fff'>".$model->dim->nama."</a>";
                },
            ],
            [
                'attribute' => 'akumulasi_skor',
                'label' => 'Skor',
                'format' => 'raw',
                'value' => 'akumulasi_skor',
            ],
            'nilai_huruf',
            [
                'attribute' => 'dim_angkatan',
                'label' => 'Angkatan',
                'headerOptions' => ['style' => 'width:20px'],
                'format' => 'raw',
                'value' => 'dim.thn_masuk',
            ],
            [
                'attribute'=>'dim_asrama',
                'label' => 'Asrama',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:80px'],
                'value' => 'dim.dimAsrama.kamar.asrama.name',
            ],

            ['class' => 'common\components\ToolsColumn',
                'template' => '{view}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    },
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
