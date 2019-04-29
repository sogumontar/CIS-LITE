<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;
use backend\modules\rakx\models\ProgramHasSumberDana;

$uiHelper = \Yii::$app->uiHelper;
?>

<?= $uiHelper->renderContentSubHeader("Total Biaya Program/Kegiatan: Rp".number_format($program->jumlah,2,',','.')); ?>

<?= GridView::widget([
            'dataProvider' => $dataProviderDana,
            'filterModel' => $searchModelDana,
            'showFooter' => true,
            'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'sumber_dana_id',
                    'value' => 'sumberDana.name',
                    'label' => 'Sumber Dana',
                ],
                [
                    'attribute' => 'desc',
                    'label' => 'Deskripsi',
                    'format' => 'html',
                ],
                [
                    'attribute' => 'jumlah',
                    'filter' =>'',
                    'value' => function($model){
                        return "Rp".number_format($model->jumlah,2,',','.');
                    },
                    'footer' => ProgramHasSumberDana::getJumlah($dataProviderDana->models, 'jumlah'),
                ],
            ],
        ]); ?>