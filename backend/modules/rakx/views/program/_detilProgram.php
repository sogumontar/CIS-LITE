<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;
use backend\modules\rakx\models\DetilProgram;

$uiHelper = \Yii::$app->uiHelper;
?>

<?= $uiHelper->renderContentSubHeader("Total Biaya Program/Kegiatan: Rp".number_format($program->jumlah,2,',','.')); ?>

<?php if($program->auth_dana_detil_cud) { ?>
<div class="pull-right">
    <?=$uiHelper->renderButtonSet([
        'template' => ['add'],
        'buttons' => [
            'add' =>['url' => Url::to(['detil-program/detil-program-add',
                'program_id'=>$program->program_id,
                'kode_program'=>$program->kode_program,
                'name'=>$program->name,
                'jumlah'=>$program->jumlah
            ]), 'label' => 'Tambah Breakdown', 'icon' => 'fa fa-plus'],
        ]
    ]) ?>
</div>
<?php } ?>

<?php if($program->auth_dana_detil_cud) { ?>
<?= GridView::widget([
            'dataProvider' => $dataProviderDetil,
            'filterModel' => $searchModelDetil,
            'showFooter' => true,
            'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'name',
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
                    'footer' => DetilProgram::getJumlah($dataProviderDetil->models, 'jumlah'),
                ],
                [
                    'class' => 'common\components\ToolsColumn',
                    'template' => '{edit} {del}',
                    'urlCreator' => function ($action, $model, $key, $index){
                        if ($action === 'edit') {
                            return Url::toRoute(['detil-program/detil-program-edit', 'id' => $key, 'program_id'=>$model->program_id, 'kode_program'=>$model->program->kode_program, 'name'=>$model->program->name, 'jumlah'=>$model->program->jumlah]);
                        }else if ($action === 'del') {
                            return Url::toRoute(['detil-program/detil-program-del', 'id' => $key]);
                        }
                    }
                ],
            ],
        ]); ?>
<?php } else { ?>

<?= GridView::widget([
            'dataProvider' => $dataProviderDetil,
            'filterModel' => $searchModelDetil,
            'showFooter' => true,
            'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'name',
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
                    'footer' => DetilProgram::getJumlah($dataProviderDetil->models, 'jumlah'),
                ]
            ],
        ]); ?>

<?php } ?>
