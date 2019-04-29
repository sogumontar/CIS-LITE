<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\bootstrap\ActiveForm;
use common\components\ToolsColumn;
use backend\modules\cist\models\SuratTugas;
use backend\modules\cist\models\Pegawai;
use backend\modules\cist\models\SuratTugasAssignee;
use backend\modules\sppd\models\BiayaPerjalanan;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\cist\models\search\SuratTugasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Surat Tugas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="surat-tugas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php 
        // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <p>
        <?php //echo Html::a('Permohonan Surat Tugas Dalam Kampus', ['add-dalam-kampus'], ['class' => 'btn btn-success']); ?>
        <?= Html::a('Permohonan Surat Tugas Perjalanan Dinas', ['add-luar-kampus'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '-',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'perequest',
                'value' => 'perequest0.nama',
            ],
            // 'no_surat',
            'nama_kegiatan',
            // [
            //     'attribute' => 'tanggal_berangkat',
            //     'value' => function($data){
            //         return date('d M Y', strtotime($data->tanggal_berangkat)).' '.date('H:i', strtotime($data->tanggal_berangkat));
            //     },
            //     'format' => 'html',
            //     'filter' => '',

            // ],
            // [
            //     'attribute' => 'tanggal_kembali',
            //     'value' => function($data){
            //         return date('d M Y', strtotime($data->tanggal_kembali)).' '.date('H:i', strtotime($data->tanggal_kembali));
            //     },
            //     'format' => 'html',
            //     'filter' => '',

            // ],

            [
                'label' => 'Status',
                'value' => function($model){
                    if($model->status_id == 1){
                        return 'Pengajuan Tugas';
                    }
                    else if($model->status_id == 2){
                        return 'Review Tugas';
                    }
                    else if($model->status_id == 4){
                        return 'Ditolak';
                    }
                    else if($model->status_id == 5){
                        return 'Dibatalkan';
                    }
                    else if($model->status_id == 6){
                        return 'Diterima Atasan, Menunggu Penerbitan Surat oleh HRD';
                    }
                    else if($model->status_id == 3){
                        $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere('deleted!=1')->one();
                        $assignee = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->andWhere(['id_pegawai' => $pegawai->pegawai_id])->one();
                        $laporan = SuratTugas::getLaporan($model->surat_tugas_id);
                        $status_laporan = $laporan->status_id;
                        if(empty($assignee->biayaPerjalanan)){
                            return 'Surat Diterbitkan, Menunggu Pembuatan SPPD oleh Sekretaris Rektorat';
                        }
                        else if(!empty($assignee->biayaPerjalanan)){
                            $biaya = BiayaPerjalanan::find()->where(['surat_tugas_assignee_id' => $assignee->surat_tugas_assignee_id])->one();
                            if($biaya->status_rencana_sekretariat == 0){
                                return 'Surat Diterbitkan, Menunggu Pembuatan SPPD oleh Sekretaris Rektorat';
                            }
                            else if($biaya->status_rencana_sekretariat == 1){
                                if($biaya->no_spj == null){
                                    return 'Surat Diterbitkan, Menunggu Penerbitan SPPD oleh Sekretaris Rektorat';
                                }
                                else{
                                    if($biaya->status_koordinator_keuangan == 0){
                                        return 'Surat Diterbitkan, Menunggu Persetujuan SPPD oleh Koordinator Keuangan';
                                    }
                                    else if($biaya->status_koordinator_keuangan == 1){
                                        if($biaya->status_wr == 0){
                                            return 'Surat Diterbitkan, Menunggu Persetujuan SPPD oleh WR2';
                                        }
                                        else if($biaya->status_wr == 1){
                                            if($biaya->status_rencana_keuangan == 0){
                                                return 'Surat Diterbitkan, Menunggu Konfirmasi SPPD oleh Keuangan';
                                            }
                                            else if($biaya->status_rencana_keuangan == 1){
                                                return 'Surat Diterbitkan, Dana SPPD Tersedia';
                                            }
                                            else if($biaya->status_rencana_keuangan == 2){
                                                if($biaya->status_realisasi_dana == 0){
                                                    return 'Dana SPPD Telah Diambil, Menunggu Realisasi dan Laporan';
                                                }
                                                else if($biaya->status_realisasi_dana == 1){
                                                    if($biaya->status_realisasi_keuangan == 0){
                                                        if($biaya->total>$biaya->total_realisasi){
                                                            if($status_laporan == 7){
                                                                return 'Laporan telah dimasukkan dan Menunggu Pengembalian Dana';
                                                            }
                                                            else if($status_laporan == 8){
                                                                return 'Laporan belum dimasukkan dan Menunggu Pengembalian Dana';
                                                            }
                                                            else if($status_laporan == 9){
                                                                return 'Laporan diterima dan Menunggu Pengembalian Dana';
                                                            }
                                                            else if($status_laporan == 10){
                                                                return 'Laporan direview dan Menunggu Pengembalian Dana';
                                                            }
                                                        }
                                                        else if($biaya->total<$biaya->total_realisasi){
                                                            if($status_laporan == 7){
                                                                return 'Laporan telah dimasukkan dan Menunggu Dana Kurang Tersedia';
                                                            }
                                                            else if($status_laporan == 8){
                                                                return 'Laporan belum dimasukkan dan Menunggu Dana Kurang Tersedia';
                                                            }
                                                            else if($status_laporan == 9){
                                                                return 'Laporan diterima dan Menunggu Dana Kurang Tersedia';
                                                            }
                                                            else if($status_laporan == 10){
                                                                return 'Laporan direview dan Menunggu Dana Kurang Tersedia';
                                                            }
                                                        }
                                                    }
                                                    else if($biaya->status_realisasi_keuangan == 1){
                                                        if($biaya->total>$biaya->total_realisasi){
                                                            if($status_laporan == 7){
                                                                return 'Laporan telah dimasukkan dan Dana Lebih Telah Dikembalikan';
                                                            }
                                                            else if($status_laporan == 8){
                                                                return 'Laporan belum dimasukkan dan Dana Lebih Telah Dikembalikan';
                                                            }
                                                            else if($status_laporan == 9){
                                                                return 'Laporan diterima dan Dana Lebih Telah Dikembalikan (Selesai)';
                                                            }
                                                            else if($status_laporan == 10){
                                                                return 'Laporan direview dan Dana Lebih Telah Dikembalikan';
                                                            }
                                                        }
                                                        else if($biaya->total<$biaya->total_realisasi){
                                                            if($status_laporan == 7){
                                                                return 'Laporan telah dimasukkan dan Dana Kurang Tersedia';
                                                            }
                                                            else if($status_laporan == 8){
                                                                return 'Laporan belum dimasukkan dan Dana Kurang Tersedia';
                                                            }
                                                            else if($status_laporan == 9){
                                                                return 'Laporan diterima dan Dana Kurang Tersedia';
                                                            }
                                                            else if($status_laporan == 10){
                                                                return 'Laporan direview dan Dana Kurang Tersedia';
                                                            }
                                                        }
                                                    }
                                                    else if($biaya->status_realisasi_keuangan == 2){
                                                        if($status_laporan == 7){
                                                            return 'Laporan telah dimasukkan dan Dana Kurang Telah Diambil';
                                                        }
                                                        else if($status_laporan == 8){
                                                            return 'Laporan belum dimasukkan dan Dana Kurang Telah Diambil';
                                                        }
                                                        else if($status_laporan == 9){
                                                            return 'Laporan diterima dan Dana Kurang Telah Diambil (Selesai)';
                                                        }
                                                        else if($status_laporan == 10){
                                                            return 'Laporan direview Dana Kurang Telah Diambil';
                                                        }
                                                    }
                                                }
                                                else if($biaya->status_realisasi_dana == 2){
                                                    if($status_laporan == 7){
                                                        return 'Laporan telah dimasukkan dan Realisasi Dana Sesuai';
                                                    }
                                                    else if($status_laporan == 8){
                                                        return 'Laporan belum dimasukkan dan Realisasi Dana Sesuai';
                                                    }
                                                    else if($status_laporan == 9){
                                                        return 'Laporan diterima dan Realisasi Dana Sesuai (Selesai)';
                                                    }
                                                    else if($status_laporan == 10){
                                                        return 'Laporan direview dan Realisasi Dana Sesuai';
                                                    }
                                                }
                                            }
                                        }
                                        else if($biaya->status_wr == 2){
                                            return 'Surat Diterbitkan, SPPD Ditolak oleh WR2';
                                        }
                                    }
                                    else if($biaya->status_koordinator_keuangan == 2){
                                        return 'Surat Diterbitkan, SPPD Ditolak oleh Koordinator Keuangan';
                                    }
                                }                                
                            }
                        }
                    }
                        
                }
            ],

            ['class' => 'common\components\ToolsColumn',
                'template' => '{view}{edit}{batalkan}{view-biaya}{print}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'Lihat Detail', 'fa fa-eye');
                    },
                    'print' => function($url, $model){
                        if($model->status_id == 3){
                            return ToolsColumn::renderCustomButton($url, $model, 'Unduh Surat Tugas', 'fa fa-print');
                        }                        
                    },
                    'edit' => function($url, $model){
                        if($model['status_id'] == 1)
                            return ToolsColumn::renderCustomButton($url, $model, 'Ubah Surat Tugas', 'fa fa-edit');
                    },
                    'batalkan' => function($url, $model){
                        if($model['status_id'] == 1)
                            return "<li>".Html::a('<span class="fa fa-remove"></span> Batalkan Surat Tugas', $url, [
                                'title' => Yii::t('yii', 'Batalkan Permohonan Surat Tugas'),
                                'data-confirm' => Yii::t('yii', 'Batalkan Permohonan Surat Tugas?'),
                                'data-method' => 'post',
                                 'data-pjax' => '0',
                            ])."</li>";
                    },
                    'view-biaya' => function($url, $model){
                        $pegawai = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->one();
                        if(!empty($pegawai->biayaPerjalanan)){
                            $bp = BiayaPerjalanan::find()->where(['surat_tugas_assignee_id' => $pegawai->surat_tugas_assignee_id])->one();
                            if($bp->status_rencana_sekretariat == 1){
                                return ToolsColumn::renderCustomButton($url, $model, 'Lihat Biaya Perjalanan', 'fa fa-eye');
                            }                            
                        }
                    },
                ],
                'urlCreator' => function($action, $model, $key, $index){
                    if($action === 'view'){
                        return Url::toRoute(['view-pegawai', 'id' => $model['surat_tugas_id']]);
                    }
                    if($action === 'print'){
                        return Url::toRoute(['create-pdf', 'id' => $model['surat_tugas_id']]);
                    }
                    else if($action === 'edit'){
                        if($model['status_id'] == 1){
                            return Url::toRoute(['edit-luar-kampus', 'id' => $model['surat_tugas_id']]);
                        }
                    }
                    else if($action === 'batalkan'){
                        if($model['status_id'] == 1){
                            return Url::toRoute(['batalkan', 'id' => $model['surat_tugas_id']]);
                        }
                    }
                    else if($action === 'view-biaya'){
                        $pegawais = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere('deleted!=1')->one();
                        $pegawai = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->andWhere(['id_pegawai' => $pegawais->pegawai_id])->one();
                        if(!empty($pegawai->biayaPerjalanan) ){
                            $bp = BiayaPerjalanan::find()->where(['surat_tugas_assignee_id' => $pegawai->surat_tugas_assignee_id])->one();
                            if($bp->status_rencana_keuangan != 2 || $bp->status_realisasi_dana == 1){
                                return Url::toRoute(['/sppd/biaya-perjalanan/view-pemohon', 'id' => $bp->biaya_perjalanan_id]);
                            }
                            else{
                                return Url::toRoute(['/sppd/biaya-perjalanan/add-biaya-pemohon', 'id' => $pegawai->surat_tugas_assignee_id]);
                            }                            
                        }
                    }
                }
            ],
        ],
    ]); ?>

</div>
