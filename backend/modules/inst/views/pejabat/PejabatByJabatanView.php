<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\modules\adak\models\Pengajaran */

$this->title = $jabatan->jabatan;
if($otherRenderer==2){
    $this->params['breadcrumbs'][] = ['label' => 'Instansi Manager', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $instansi_name, 'url' => ['inst-manager/strukturs?instansi_id='.$instansi_id]];
    $this->params['breadcrumbs'][] = $this->title;
}else if($otherRenderer){
    $this->params['breadcrumbs'][] = ['label' => 'History Pejabat', 'url' => ['pejabat-history-all-view']];
    $this->params['breadcrumbs'][] = $this->title;
}else{
    $this->params['breadcrumbs'][] = ['label' => 'Management Pejabat', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}
$this->params['header'] = 'Struktur Jabatan '.$this->title;

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pejabat-by-jabatan-view">

    <?= $uiHelper->renderContentSubHeader('Detil Jabatan', ['icon' => 'fa fa-book']);?>
    <?= $uiHelper->renderLine(); ?>

    <div class="pull-right">
        <?php
        $uiHelper->renderButtonSet([
            'template' => ['add'],
            'buttons' => [
                'add' => [  'url' => Url::to([
                                        'pejabat-add',
                                        'jabatan_id' => $jabatan_id,
                                        ]), 
                            'label' => 'Tambahkan Pejabat', 
                            'icon' => 'fa fa-book'
                        ],
            ]
        ]);
        ?>

    </div>
    <?= $uiHelper->beginSingleRowBlock(['id' => 'grid-detail',
            //'width' => 4,
            // 'header' => $this->title,
        ]) ?>
    <?= DetailView::widget([
        'model' => $jabatan,
        'options' =>[
            'class' => 'table table-condensed detail-view'
        ],
        'attributes' => [
            [
                'label' => 'Nama Jabatan',
                'attribute' => 'jabatan',
            ],
            [
                'attribute' => 'atasan',
                'label' => 'Atasan',
                'value' => function($data){
                    if(is_null($data['parent']))
                        return '-';
                    else return $data['parent0']->jabatan;
                },
            ],
            [
                'attribute' => 'is_multi_tenant',
                'value' => function($data){
                    return $data['is_multi_tenant']==0?'Single':'Multi Tenant';
                },
            ],
            [
                'attribute' => 'mata_anggaran',
                'value' => function($data){
                    return $data['mata_anggaran']==0?'Tidak':'Ya';
                },
            ],
            [
                'attribute' => 'laporan',
                'value' => function($data){
                    return $data['laporan']==0?'Tidak':'Ya';
                },
            ],
            [
                'attribute' => 'unit',
                'label' => 'Unit',
                'value' => function($data){
                    if(is_null($data['unit_id']))
                        return '-';
                    else return $data['unit']->name;
                },
            ],
            [
                'label' => 'Instansi',
                'attribute' => 'instansi.name',
            ],
        ],
    ]) ?>
    <?=$uiHelper->endSingleRowBlock()?>

    <?= $uiHelper->renderContentSubHeader('Pejabat yang Tengah Menjabat', ['icon' => 'fa fa-book']);?>
    <?=$uiHelper->renderLine(); ?>

    <?php $form = ActiveForm::begin([   'layout' => 'horizontal',
                                        'fieldConfig' => [
                                            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                                            'horizontalCssClasses' => [
                                                'label' => 'col-sm-4',
                                                'wrapper' => 'col-sm-8',
                                                'error' => '',
                                                'hint' => '',
                                            ],
                                        ],
    ]); ?>
 
    <?= $uiHelper->beginSingleRowBlock(['id' => 'grid-now',
            //'width' => 4,
            // 'header' => $this->title,
        ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProviderNow,
        'tableOptions' => ['class' => 'table table-condensed table-bordered'],
        'rowOptions' => function ($data, $key, $index, $grid){
                            $mbf = strtotime(date('Y-m-d') .' +2 months');
                            $mbf = date('Y-m-d', $mbf);
                            $dbf = strtotime(date('Y-m-d') .' +1 days');
                            $dbf = date('Y-m-d', $dbf);
                            if($data->status_aktif == 1 && $data->akhir_masa_kerja < date('Y-m-d')){
                                return ['class' => 'bg-red'];
                            }else if($data->status_aktif == 1 && $data->akhir_masa_kerja > date('Y-m-d') && $data->akhir_masa_kerja <= $mbf){
                                return ['class' => 'bg-yellow'];
                            }else if($data->status_aktif == 0 && $data->awal_masa_kerja <= $dbf && $data->akhir_masa_kerja > date('Y-m-d')){
                                return ['class' => 'bg-green'];
                            }
                        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Nama Pegawai',
                //'attribute' => 'pegawai_id',
                'value' => 'pegawai.nama',
            ],
            [
                'label' => 'NIP',
                'attribute' => 'nip',
                'value' => 'pegawai.nip',
            ],
            [
                'attribute' => 'awal_masa_kerja',
                'format' => ['date', 'php:d M Y']
            ],
            [
                'attribute' => 'akhir_masa_kerja',
                'format' => ['date', 'php:d M Y']
            ],
            ['class' => 'common\components\ToolsColumn',
                 'template' => '{aktif} {extend} {nonaktif} {view} {edit}',
                'header' => 'Aksi',
                'buttons' => [
                    'aktif' => function ($url, $model){
                        $dbf = strtotime(date('Y-m-d') .' +1 days');
                        $dbf = date('Y-m-d', $dbf);
                        if($model->status_aktif == 0 && $model->awal_masa_kerja <= $dbf && $model->akhir_masa_kerja > date('Y-m-d')){
                            return ToolsColumn::renderCustomButton($url, $model, 'Aktifkan', 'fa fa-toggle-on');
                        }
                    },
                    'extend' => function ($url, $model){
                        $mbf = strtotime(date('Y-m-d') .' +2 months');
                        $mbf = date('Y-m-d', $mbf);
                        if($model->status_aktif == 1 && $model->akhir_masa_kerja > date('Y-m-d') && $model->akhir_masa_kerja <= $mbf){
                            return ToolsColumn::renderCustomButton($url, $model, 'Perbaharui Kontrak', 'fa fa-repeat');
                        }
                    },
                    'nonaktif' => function ($url, $model){
                        if($model->status_aktif == 1){
                            //$script = <<< JS alert("Hi");
                            //JS;
                            return ToolsColumn::renderCustomButton($url, $model, 'Nonaktifkan', 'fa fa-power-off'/*, [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Apakah anda yakin akan Menonaktifkan Pejabat ini ?',
                                'method' => 'post',
                            ]]*/);
                        }
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'aktif') {
                        return Url::toRoute(['pejabat-status-aktif-edit', 'id' => $key]);
                    }else if ($action === 'extend') {
                        return Url::toRoute(['pejabat-extend-kontrak-add', 'id' => $key]);
                    }else if ($action === 'nonaktif') {
                        return Url::toRoute(['pejabat-status-nonaktif-edit', 'id' => $key, 'renderer' => 2]);
                    }else if ($action === 'view') {
                        return Url::toRoute(['pejabat-view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['pejabat-edit', 'id' => $key]);
                    }
                }
            ],
        ],
    ]); ?>

    <?=$uiHelper->endSingleRowBlock()?>
    <?php ActiveForm::end(); ?>

    <?= $uiHelper->renderContentSubHeader('History Pegawai yang Pernah Menjabat', ['icon' => 'fa fa-history']);?>
    <?=$uiHelper->renderLine(); ?>

    <?php $form = ActiveForm::begin([   'layout' => 'horizontal',
                                        'fieldConfig' => [
                                            'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                                            'horizontalCssClasses' => [
                                                'label' => 'col-sm-4',
                                                'wrapper' => 'col-sm-8',
                                                'error' => '',
                                                'hint' => '',
                                            ],
                                        ],
    ]); ?>
    <?= $uiHelper->beginSingleRowBlock(['id' => 'grid-old',
            //'width' => 4,
            // 'header' => $this->title,
        ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProviderOld,
        'tableOptions' => ['class' => 'table table-condensed table-bordered'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Nama Pegawai',
                //'attribute' => 'pegawai_id',
                'value' => 'pegawai.nama',
            ],
            [
                'label' => 'NIP',
                'attribute' => 'nip',
                'value' => 'pegawai.nip',
            ],
            [
                'attribute' => 'awal_masa_kerja',
                'format' => ['date', 'php:d M Y']
            ],
            [
                'attribute' => 'akhir_masa_kerja',
                'format' => ['date', 'php:d M Y']
            ],
        ],
    ]); ?>

    <?=$uiHelper->endSingleRowBlock()?>
    <?php ActiveForm::end(); ?>

</div>
