<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use backend\modules\cist\models\SuratTugas;
use common\components\ToolsColumn;

?>
<div class="profil-pegawai" style="margin-top: 50px;">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nama',
            'nip',
            'posisi',
            'telepon',
        ],
    ]); ?>
    <hr>
    <h1>Riwayat permohonan surat tugas perjalanan dinas pegawai</h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '-',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'perequest0.nama',
            'no_surat',
            'nama_kegiatan',
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
                'attribute' => 'kembali_bekerja',
                'format' => 'raw',
                'value' => function($model){
                    if(is_null($model->kembali_bekerja)){
                        return '-';
                    }
                    else{
                        return date('d M Y H:i', strtotime($model->kembali_bekerja));
                    }
                }
            ],
            [
                'label' => 'Status Surat Tugas',
                'attribute' => 'name',
                'value' => 'statusName.name',
            ],
            [
                'label' => 'Status Laporan',
                'value' => function($model){
                    return SuratTugas::getStatusLaporan($model->surat_tugas_id);
                }
            ],
    
            ['class' => 'common\components\ToolsColumn',
                'template' => '{view}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'Lihat Surat Tugas', 'fa fa-eye');
                    },
                ],
                'urlCreator' => function($action, $model, $key, $index){
                    if($action === 'view'){
                        $url = 'view-surat-bawahan?id=' . $model['surat_tugas_id'];
                        
                        return $url;
                    }
                }
            ],
        ],
    ]) ?>
    <?= Html::a('Kembali', ['view-surat-bawahan?id=' . $suratTugasId], ['class' => 'btn btn-primary']) ?>
</div>