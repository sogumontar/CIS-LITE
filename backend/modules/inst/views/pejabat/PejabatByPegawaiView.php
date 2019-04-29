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

$this->title = $pegawai->nama;
$this->params['breadcrumbs'][] = ['label' => 'Management Pejabat', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Pejabat '.$this->title;

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pejabat-by-pegawai-view">

    <?= $uiHelper->renderContentSubHeader('Profil', ['icon' => 'fa fa-user']);?>
    <?=$uiHelper->renderLine(); ?>

    <div class="pull-right">
        <?php
        $uiHelper->renderButtonSet([
            'template' => ['add'],
            'buttons' => [
                'add' => [  'url' => Url::to([
                                        'pejabat-add',
                                        'pegawai_id' => $pegawai_id,
                                        ]), 
                            'label' => 'Tambah Jabatan ke Pegawai', 
                            'icon' => 'fa fa-book'
                        ],
            ]
        ]);
        ?>

    </div>
    <?= $uiHelper->beginSingleRowBlock(['id' => 'grid-profil',
            //'width' => 4,
            // 'header' => $this->title,
        ]) ?>
    <?= DetailView::widget([
        'model' => $pegawai,
        'options' =>[
            'class' => 'table table-condensed detail-view'
        ],
        'attributes' => [
            [
                'label' => 'Nama Pegawai',
                'attribute' => 'nama',
            ],
            'nip',
        ],
    ]) ?>
    <?=$uiHelper->endSingleRowBlock()?>

    <?= $uiHelper->renderContentSubHeader('Jabatan yang Tengah Dijabat', ['icon' => 'fa fa-book']);?>
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
                'label' => 'Jabatan',
                'attribute' => 'struktur_jabatan_id',
                'value' => 'strukturJabatan.jabatan',
            ],
            [
                'attribute' => 'awal_masa_kerja',
                'format' => ['date', 'php:d M Y']
            ],
            [
                'attribute' => 'akhir_masa_kerja',
                'format' => ['date', 'php:d M Y']
            ],
            [
                'attribute' => 'atasan',
                'label' => 'Atasan',
                'value' => 'strukturJabatan.parent0.jabatan',
                'value' => function($data){
                    if(is_null($data['strukturJabatan']->parent))
                        return '-';
                    else return $data['strukturJabatan']['parent0']->jabatan;
                },
            ],
            [
                'attribute' => 'unit',
                'label' => 'Unit',
                'value' => function($data){
                    if(is_null($data['strukturJabatan']->unit_id))
                        return '-';
                    else return $data['strukturJabatan']['unit']->name;
                },
            ],
            [
                'attribute' => 'instansi',
                'label' => 'Instansi',
                'value' => 'strukturJabatan.instansi.inisial',
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
                        return Url::toRoute(['pejabat-status-nonaktif-edit', 'id' => $key, 'renderer' => 1]);
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

    <?= $uiHelper->renderContentSubHeader('History Jabatan yang Pernah Dijabat', ['icon' => 'fa fa-history']);?>
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
                'label' => 'Jabatan',
                'attribute' => 'struktur_jabatan_id',
                'value' => 'strukturJabatan.jabatan',
            ],
            [
                'attribute' => 'awal_masa_kerja',
                'format' => ['date', 'php:d M Y']
            ],
            [
                'attribute' => 'akhir_masa_kerja',
                'format' => ['date', 'php:d M Y']
            ],
            /*[
                'attribute' => 'unit',
                'label' => 'Unit',
                'value' => function($data){
                    if(is_null($data['strukturJabatan']->unit_id))
                        return '-';
                    else return $data['strukturJabatan']['unit']->name;
                },
            ],*/
            [
                'attribute' => 'instansi',
                'label' => 'Instansi',
                'value' => 'strukturJabatan.instansi.inisial',
            ],
        ],
    ]); ?>

    <?=$uiHelper->endSingleRowBlock()?>
    <?php ActiveForm::end(); ?>

</div>
