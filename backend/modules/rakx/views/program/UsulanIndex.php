<?php

use yii\helpers\Html;
use yii\web\View;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\components\ToolsColumn;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use backend\modules\rakx\models\Program;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rakx\models\search\ProgramSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usul Program';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Usul Program';
$this->params['layout'] = 'full';

$uiHelper = \Yii::$app->uiHelper;

?>

<div class="program-search">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'method'=>'get',
        'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
            'horizontalCssClasses' => [
            'label' => 'col-md-4',
            'offset' => 'col-md-offset-10',
            'wrapper' => 'col-md-0',
            'error' => '',
            'hint' => '',
        ],
    ],
    ]); ?>

    <?=$uiHelper->beginContentRow() ?>

        <?= $uiHelper->beginContentBlock(['id' => 'grid-system1', 'width' => 6, ]) ?>
                <?= $form->field($searchModel, 'tahun_anggaran', [
                           'horizontalCssClasses' => ['wrapper' => 'col-sm-4',]])->label("Tahun Anggaran")->dropDownList(
                    ArrayHelper::map($tahun_anggaran, 'tahun_anggaran_id', 'tahun'),
                        ['options' => [$tahun_anggaran[0]->tahun_anggaran_id => ['Selected'=>'selected']],
                        'onchange' => 'this.form.submit()'])
                ?>
        <?=$uiHelper->endContentBlock()?>

    <?=$uiHelper->endContentRow() ?>

    <?php ActiveForm::end(); ?>

</div>

<?=$uiHelper->renderContentSubHeader(' Total: <b>'.$searchModel->total.'</b>') ?>
<?=$uiHelper->renderLine() ?>

<div class="program-index">

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter' => true,
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'mata_anggaran',
                'value' => 'strukturJabatanHasMataAnggaran.mataAnggaran.name',
                'label' => 'Mata Anggaran',
                'filter' => ArrayHelper::map($mata_anggaran, 'mata_anggaran_id', function($mata_anggaran){ return $mata_anggaran->kode_anggaran.' '.$mata_anggaran->name;}),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
            ],
            'kode_program',
            [
                'attribute' => 'name',
                'footer' => '<div style="text-align:right;font-weight:bold;">Total</div>',
            ],
            [
                'attribute' => 'struktur_jabatan',
                'value' => 'strukturJabatanHasMataAnggaran.strukturJabatan.jabatan',
                'label' => 'Diusulkan ke',
                'filter' => ArrayHelper::map($struktur_jabatan, 'struktur_jabatan_id', 'jabatan'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
            ],
            [
                'attribute' => 'jumlah',
                'filter' => '',
                'value' => function($model){
                    return "Rp".number_format($model->jumlah,2,',','.');
                },
                'footer' => Program::getJumlah($dataProvider->models, 'jumlah'),
            ],
            [
                'attribute' => 'status_program_id',
                'value' => function ($model) use ($uiHelper){
                    if(!isset($model->statusProgram)) return '';
                    if($model->statusProgram->status_program_id==0 || $model->statusProgram->status_program_id==6)
                        return '<p style="color:black;font-weight:bold;font-style:italic">'.$model->statusProgram->name.' <a href="#" data-toggle="tooltip" data-placement="top" title="'.\yii\helpers\HtmlPurifier::process(Program::isProgramValid($model->program_id)[0]).'"><i class="fa fa-info-circle"></i></a></p>';
                    else if($model->statusProgram->status_program_id==2 || $model->statusProgram->status_program_id==7)
                        return '<p style="color:blue;font-weight:bold">'.$model->statusProgram->name.'</p>';
                    else if($model->statusProgram->status_program_id==3)
                        return '<p style="color:green;font-weight:bold">'.$model->statusProgram->name.'</p>';
                    else if($model->statusProgram->status_program_id==5)
                        return '<p style="background-color:green;color:white;font-weight:bold;text-align:center">'.$model->statusProgram->name.'</p>';
                    else if($model->statusProgram->status_program_id==4)
                        return '<p style="color:red;font-weight:bold">'.$model->statusProgram->name.'</p>';
                    else if($model->statusProgram->status_program_id==8)
                        return '<p style="color:purple;font-weight:bold">'.$model->statusProgram->name.'</p>';
                    else
                        return '<p style="color:orange;font-weight:bold">'.$model->statusProgram->name.'</p>';
                },
                'format' => 'html',
                'label' => 'Status Program',
                'filter' => ArrayHelper::map($status_program, 'status_program_id', 'name'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
            ],
            /*[
                'value' => function($model) use ($pejabat_id){
                    return $model->diusulkan_oleh == $pejabat_id;
                }
            ],*/
            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {edit} {dana} {breakdown} {del}',
                'buttons' => [
                    'del' => function ($url, $model){
                        if($model->status_program_id==0 || $model->status_program_id==1){
                            return "<li>".Html::a('<span class="glyphicon glyphicon-trash"></span> Delete', $url, [
                                'title' => Yii::t('yii', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                                 'data-pjax' => '0',
                            ])."</li>";
                        }
                    },
                    'edit' => function ($url, $model){
                        if($model->status_program_id==0 || $model->status_program_id==1 || $model->status_program_id==2){
                            return ToolsColumn::renderCustomButton($url, $model, $model->status_program_id==0?'Waktu Pelaksanaan':'Edit', 'fa fa-pencil');
                        }
                    },
                    'dana' => function ($url, $model){
                        if($model->status_program_id==0){
                            return ToolsColumn::renderCustomButton($url, $model, 'Sumber Dana', 'fa fa-money');
                        }
                    },
                    'breakdown' => function ($url, $model){
                        if($model->status_program_id==0){
                            return ToolsColumn::renderCustomButton($url, $model, 'Breakdown', 'fa fa-puzzle-piece');
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['program-usulan-view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['program-usulan-edit', 'id' => $key]);
                    }else if ($action === 'del') {
                        return Url::toRoute(['program-del', 'id' => $key]);
                    }else if ($action === 'dana') {
                        return Url::toRoute(['program-usulan-view', 'id' => $key, 'tab' => 'data_dana']);
                    }else if ($action === 'breakdown') {
                        return Url::toRoute(['program-usulan-view', 'id' => $key, 'tab' => 'data_detil']);
                    }
                }
            ],
        ],
    ]);
    Pjax::end() ?>

</div>

<?php 
  $this->registerJs("
    $(document).ready(function() {
        $('[data-toggle=\"tooltip\"]').tooltip();
    });
  ", 
    View::POS_END);
?>