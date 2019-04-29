<?php

use dosamigos\highcharts\HighCharts;
use backend\modules\cist\models\SuratTugas;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

?>
<?php
    $requested = SuratTugas::getTotRequest();
    $accepted = SuratTugas::getTotDiterima();
    $rejected = SuratTugas::getTotDitolak();
    $published = SuratTugas::getTotDiterbitkan();
?>
<div class="col-md-12">
    <div class="col-md-6">
        <?=HighCharts::widget([
            'clientOptions' => [
                'chart' => [
                    'type' => 'pie',
                    'height' => '300'
                ],
                'title' => [
                    'text' => 'Total layanan surat tugas'
                ],
                'plotOptions' => [
                    'pie' => [
                        'dataLabels' => [
                            'enabled'   => true
                        ],
                        'enableMouseTracking' => true
                    ]
                ],
                'series' => [
                    [
                        'name' => 'data',
                        'colorByPoint' => true,
                        'data' => [
                            [
                                'name' => 'Request',
                                'y' => $requested,
                                'color' => '#7cb5ec'
                            ],
                            [
                                'name' => 'Diterima',
                                'y' => $accepted,
                                'color' => '#ACFC00'
                            ],
                            [
                                'name' => 'Ditolak',
                                'y' => $rejected,
                                'color' => '#FF2E2E'
                            ],
                            [
                                'name' => 'Diterbitkan',
                                'y' => $published,
                                'color' => '#2EFFB0'
                            ],
                        ],
                    ],
                ],
                'credits' => [
                    'enabled' => false,
                ]
            ]
        ]); ?>
    </div>
    <div class="col-md-6" style="margin-top: 65px;">
    <?= DetailView::widget([
        'model' => '',
        'attributes' => [
            [
                'label' => 'Total Request',
                'value' => function(){
                    return SuratTugas::getTotRequest();
                },
            ],
            [
                'label' => 'Total Diterima',
                'value' => function(){
                    return SuratTugas::getTotDiterima();
                },
            ],
            [
                'label' => 'Total Ditolak',
                'value' => function(){
                    return SuratTugas::getTotDitolak();
                },
            ],
            [
                'label' => 'Total Diterbitkan',
                'value' => function(){
                    return SuratTugas::getTotDiterbitkan();
                },
            ],
            [
                'label' => 'Total Layanan',
                'value' => function(){
                    return SuratTugas::getTotLayanan();
                }
            ]
        ],
    ]) ?>
    </div>
    
</div>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    // 'filterModel' => $searchModel,
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'nullDisplay' => '-',
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'perequest0.nama',
        'no_surat',
        'agenda',
        [
            'attribute' => 'tanggal_berangkat',
            'value' => function($data){
                return date('d M Y', strtotime($data->tanggal_berangkat)).' '.date('H:i', strtotime($data->tanggal_berangkat));
            },
            'format' => 'html',
            'filter' => '',

        ],
        [
            'attribute' => 'tanggal_kembali',
            'value' => function($data){
                return date('d M Y', strtotime($data->tanggal_kembali)).' '.date('H:i', strtotime($data->tanggal_kembali));
            },
            'format' => 'html',
            'filter' => '',

        ],
        [
            'label' => 'Status Surat Tugas',
            'attribute' => 'name',
            'value' => 'statusName.name',
        ],
        // [
        //         'label' => 'Status Surat Tugas',
        //         'attribute' => 'name',
        //         'value' => 'statusName.name',
        //         'filter' => ArrayHelper::map($status, 'status_id', 'name'),
        //         'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
        //         'headerOptions' => ['style' => 'width:15%'],
        // ],
        [
            'label' => 'Status Laporan',
            'value' => function($model){
                return SuratTugas::getStatusLaporan($model->surat_tugas_id);
            }
        ],

        // ['class' => 'common\components\ToolsColumn',
        //     'template' => '{view}{edit}',
        //     'header' => 'Aksi',
        //     'buttons' => [
        //         'view' => function($url, $model){
        //             return ToolsColumn::renderCustomButton($url, $model, 'Lihat Surat Tugas', 'fa fa-eye');
        //         },
        //         'edit' => function($url, $model){
        //             if($model['name'] == 1)
        //                 return ToolsColumn::renderCustomButton($url, $model, 'Ubah Surat Tugas', 'fa fa-edit');
        //         },
        //     ],
        //     'urlCreator' => function($action, $model, $key, $index){
        //         if($action === 'view'){
        //             $url = 'view-pegawai?id=' . $model['surat_tugas_id'];
                    
        //             return $url;
        //         }else if($action === 'edit'){
        //             $url = 'index-pegawai';
                    
        //             if($model['name'] == 1){
        //                 $url = 'edit-luar-kampus?id=' . $model['surat_tugas_id'];
        //             }

        //             return $url;
        //         }
        //     }
        // ],
    ],
]); ?>
