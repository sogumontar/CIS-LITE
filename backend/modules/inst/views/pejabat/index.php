<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\SwitchColumn;
use common\components\ToolsColumn;
use common\helpers\LinkHelper;
use yii\web\View;
use backend\modules\inst\assets\InstAsset;

InstAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\inst\models\search\PejabatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manajemen Pejabat';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Manajemen Pejabat';

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pejabat-index">

    <?= $uiHelper->renderContentSubHeader('List '.$this->title, ['icon' => 'fa fa-list']);?>
    <?=$uiHelper->renderLine(); ?>
    <?= $uiHelper->beginSingleRowBlock(['id' => 'grid-pejabat',]) ?>
    <?php
        echo 'Silahkan pilih ' . Html::a('Struktur Jabatan', Url::to(['inst-manager/index'])) . ' dari Instansi.<br />';
    ?>
    <?=$uiHelper->endSingleRowBlock()?>

    <?= $uiHelper->beginContentBlock(['id' => 'pejabat-info',
            // 'width' => 4,
            'height' => 12,
        ]) ?>
        <div class="callout callout-info">
        <?php
            echo 'Pilih <b>Nama Pegawai</b> untuk melihat Jabatan Yang Tengah Dijabat, History Jabatan, dan untuk Tambah Jabatan<br />';
            echo 'Pilih <b>Jabatan</b> untuk melihat Pejabat Yang Tengah Menjabat, History Pejabat, dan untuk Tambah Pegawai menjadi Pejabat';
        ?>
        </div>
    <?=$uiHelper->endContentBlock()?>

    <?php
        $active1 = ($status_expired == 0)?'active':'';
        $active2 = ($status_expired == 1)?'active':'';
        $active3 = ($status_expired == 2)?'active':'';
        $active4 = ($status_expired == 3)?'active':'';

        $toolbarItemStatusAktif =  "<a href='".Url::to(['pejabat/index', 'status_expired' => 0])."' class='btn btn-default ".$active1."'><i class='fa fa-users'></i><span class='toolbar-label'>All</span></a>
            <a href='".Url::to(['pejabat/index', 'status_expired' => 3])."' class='btn btn-success ".$active4."'><i class='fa fa-toggle-off'></i><span class='toolbar-label'>Pejabat Belum Aktif</span></a>
            <a href='".Url::to(['pejabat/index', 'status_expired' => 2])."' class='btn btn-warning ".$active3."'><i class='fa fa-hourglass-half'></i><span class='toolbar-label'>Masa Kerja 2 Bulan Akhir</span></a>
            <a href='".Url::to(['pejabat/index', 'status_expired' => 1])."' class='btn btn-danger ".$active2."'><i class='fa fa-calendar-times-o'></i><span class='toolbar-label'>Masa Kerja Expired</span></a>
            ";

    ?>

    <?=Yii::$app->uiHelper->renderToolbar([
    'pull-right' => true,
    'groupTemplate' => ['groupStatusExpired'],
    'groups' => [
        'groupStatusExpired' => [
            'template' => ['filterStatus'],
            'buttons' => [
                'filterStatus' => $toolbarItemStatusAktif,
            ]
        ],
    ],
    ]) ?>

    <?php
    Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-stripped table-condensed table-bordered'],
        'rowOptions' => function ($data, $key, $index, $grid){
                            $mbf = strtotime(date('Y-m-d') .' +2 months');
                            $mbf = date('Y-m-d', $mbf);
                            //$dbf = strtotime(date('Y-m-d') .' +1 days');
                            //$dbf = date('Y-m-d', $dbf);
                            if($data->status_aktif == 1 && $data->akhir_masa_kerja < date('Y-m-d')){
                                return ['class' => 'bg-red'];
                            }else if($data->status_aktif == 1 && $data->akhir_masa_kerja > date('Y-m-d') && $data->akhir_masa_kerja <= $mbf){
                                return ['class' => 'bg-yellow'];
                            }else if($data->status_aktif == 0 && $data->awal_masa_kerja <= date('Y-m-d') && $data->akhir_masa_kerja > date('Y-m-d')){
                                return ['class' => 'bg-green'];
                            }
                        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'pegawai_nama',
                'label' => 'Nama Pegawai',
                'format' => 'raw',
                'value' => function($data){
                    $mbf = strtotime(date('Y-m-d') .' +2 months');
                    $mbf = date('Y-m-d', $mbf);
                    //$dbf = strtotime(date('Y-m-d') .' +1 days');
                    //$dbf = date('Y-m-d', $dbf);
                    if($data['status_aktif'] == 1 && $data['akhir_masa_kerja'] < date('Y-m-d')){
                        return LinkHelper::renderLink([
                            'label' => '<strong>'.$data['pegawai']->nama.'</strong>',
                            'url' => Url::to(['pejabat-by-pegawai-view', 'pegawai_id' => $data['pegawai_id']]),
                        ]);
                    }else if($data->status_aktif == 1 && $data->akhir_masa_kerja > date('Y-m-d') && $data->akhir_masa_kerja <= $mbf){
                        return LinkHelper::renderLink([
                            'label' => '<strong>'.$data['pegawai']->nama.'</strong>',
                            'url' => Url::to(['pejabat-by-pegawai-view', 'pegawai_id' => $data['pegawai_id']]),
                        ]);
                    }else if($data->status_aktif == 0 && $data->awal_masa_kerja <= date('Y-m-d') && $data->akhir_masa_kerja > date('Y-m-d')){
                        return LinkHelper::renderLink([
                            'label' => '<strong>'.$data['pegawai']->nama.'</strong>',
                            'url' => Url::to(['pejabat-by-pegawai-view', 'pegawai_id' => $data['pegawai_id']]),
                        ]);
                    }
                    return LinkHelper::renderLink([
                        'label' => $data['pegawai']->nama,
                        'url' => Url::to(['pejabat-by-pegawai-view', 'pegawai_id' => $data['pegawai_id']]),
                    ]);
                },
            ],
            [
                'attribute' => 'jabatan_nama',
                'label' => 'Jabatan',
                'format' => 'raw',
                'value' => function($data){
                    $mbf = strtotime(date('Y-m-d') .' +2 months');
                    $mbf = date('Y-m-d', $mbf);
                    //$dbf = strtotime(date('Y-m-d') .' +1 days');
                    //$dbf = date('Y-m-d', $dbf);
                    if($data['status_aktif'] == 1 && $data['akhir_masa_kerja'] < date('Y-m-d')){
                        return LinkHelper::renderLink([
                            'label' => '<strong>'.$data['strukturJabatan']->jabatan.'</strong>',
                            'url' => Url::to(['pejabat-by-jabatan-view', 'jabatan_id' => $data['struktur_jabatan_id']]),
                        ]);
                    }else if($data->status_aktif == 1 && $data->akhir_masa_kerja > date('Y-m-d') && $data->akhir_masa_kerja <= $mbf){
                        return LinkHelper::renderLink([
                            'label' => '<strong>'.$data['strukturJabatan']->jabatan.'</strong>',
                            'url' => Url::to(['pejabat-by-jabatan-view', 'jabatan_id' => $data['struktur_jabatan_id']]),
                        ]);
                    }else if($data->status_aktif == 0 && $data->awal_masa_kerja <= date('Y-m-d') && $data->akhir_masa_kerja > date('Y-m-d')){
                        return LinkHelper::renderLink([
                            'label' => '<strong>'.$data['strukturJabatan']->jabatan.'</strong>',
                            'url' => Url::to(['pejabat-by-jabatan-view', 'jabatan_id' => $data['struktur_jabatan_id']]),
                        ]);
                    }
                    return LinkHelper::renderLink([
                        'label' => $data['strukturJabatan']->jabatan,
                        'url' => Url::to(['pejabat-by-jabatan-view', 'jabatan_id' => $data['struktur_jabatan_id']]),
                    ]);
                },
            ],
            [
                'attribute' => 'status_aktif',
                'filter' => '',
                'value' => function($data){
                    return $data['status_aktif']==1?"Aktif":"Tidak Aktif";
                },
            ],
            [
                'attribute' => 'awal_masa_kerja',
                'format' => ['date', 'php:d M Y'],
                'filter' => '',
            ],
            [
                'attribute' => 'akhir_masa_kerja',
                'format' => ['date', 'php:d M Y'],
                'filter' => '',
            ],
            /*[
                'attribute' => 'unit',
                'label' => 'Unit',
                'value' => function($data){
                    if(is_null($data['strukturJabatan']->unit_id))
                        return '-';
                    else return $data['strukturJabatan']['unit']->name;
                },
            ],
            [
                'attribute' => 'atasan',
                'label' => 'Atasan',
                'value' => function($data){
                    if(is_null($data['strukturJabatan']->parent))
                        return '-';
                    else return $data['strukturJabatan']['parent0']->jabatan;
                },
            ],*/
            [
                'attribute' => 'instansi',
                'label' => 'Instansi',
                'value' => 'strukturJabatan.instansi.inisial',
                'filter' => ArrayHelper::map($instansi, 'inisial', 'inisial'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'headerOptions' => ['style' => 'width:15%'],
            ],
            ['class' => 'common\components\ToolsColumn',
                 'template' => '{aktif} {extend} {nonaktif} {view} {edit}',
                'header' => 'Aksi',
                'buttons' => [
                    'aktif' => function ($url, $model){
                        //$dbf = strtotime(date('Y-m-d') .' +1 days');
                        //$dbf = date('Y-m-d', $dbf);
                        if($model->status_aktif == 0 && $model->awal_masa_kerja <= date('Y-m-d') && $model->akhir_masa_kerja > date('Y-m-d')){
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
                            return ToolsColumn::renderCustomButton($url, $model, 'Nonaktifkan', 'fa fa-power-off');
                        }
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'aktif') {
                        return Url::toRoute(['pejabat-status-aktif-edit', 'id' => $key]);
                    }else if ($action === 'extend') {
                        return Url::toRoute(['pejabat-extend-kontrak-add', 'id' => $key]);
                    }else if ($action === 'nonaktif') {
                        return Url::toRoute(['pejabat-status-nonaktif-edit', 'id' => $key, 'renderer' => 0]);
                    }else if ($action === 'view') {
                        return Url::toRoute(['pejabat-view', 'id' => $key]);
                    }else if($action === 'edit'){
                        return Url::toRoute(['pejabat-edit', 'id' => $key]);
                    }
                }
            ],
        ],
    ]);
    Pjax::end() ?>

</div>