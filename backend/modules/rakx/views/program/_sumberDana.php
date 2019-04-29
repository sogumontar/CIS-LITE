<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;
use backend\modules\rakx\models\ProgramHasSumberDana;

$uiHelper = \Yii::$app->uiHelper;
?>

<?= $uiHelper->renderContentSubHeader("Total Biaya Program/Kegiatan: Rp".number_format($program->jumlah,2,',','.')); ?>

<?php if($program->auth_dana_detil_cud) { ?>
<div class="pull-right">
    <?=$uiHelper->renderButtonSet([
        'template' => ['add'],
        'buttons' => [
            'add' =>['url' => Url::to(['program-has-sumber-dana/program-has-sumber-dana-add',
                'program_id'=>$program->program_id,
                'kode_program'=>$program->kode_program,
                'name'=>$program->name,
                'jumlah'=>$program->jumlah
            ]), 'label' => 'Tambah Sumber Dana', 'icon' => 'fa fa-plus'],
        ]
    ]) ?>
</div>
<?php } ?>

<?php if($program->auth_dana_detil_cud) { ?>
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
                ['class' => 'common\components\ToolsColumn',
                    'template' => '{edit} {del}',
                    'urlCreator' => function ($action, $model, $key, $index){
                        if ($action === 'edit') {
                            return Url::toRoute(['program-has-sumber-dana/program-has-sumber-dana-edit', 'id' => $key, 'program_id'=>$model->program_id, 'kode_program'=>$model->program->kode_program, 'name'=>$model->program->name, 'jumlah'=>$model->program->jumlah]);
                        }else if ($action === 'del') {
                            return Url::toRoute(['program-has-sumber-dana/program-has-sumber-dana-del', 'id' => $key]);
                        }
                    }
                ],
            ],
        ]); ?>
<?php } else { ?>
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
                ]
            ],
        ]); ?>
<?php } ?>