<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\ToolsColumn;
use backend\modules\cist\models\SuratTugas;
use backend\modules\cist\models\LaporanSuratTugas;
use backend\modules\cist\models\Pegawai;
use backend\modules\cist\models\SuratTugasAssignee;
use backend\modules\sppd\models\BiayaPerjalanan;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\cist\models\search\SuratTugasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Surat Tugas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="surat-tugas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
        Pjax::begin();
        echo GridView::widget([
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
                [
                    'label' => 'Peserta',
                    'value' => function($model){
                        $pegawais = SuratTugas::getAssignee($model->surat_tugas_id);
                        return '- '.implode('<br/>- ', array_column($pegawais, 'nama'));
                    },
                    'format' => 'html',
                ],
                'no_surat',
                'nama_kegiatan',
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
                    'label' => 'Status',
                    'value' => function($model){
                        // $pegawai = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere('deleted!=1')->one();
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
                            $assignee = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->one();  
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
                                    $laporan = SuratTugas::getLaporan($model->surat_tugas_id);
                                    $status_laporan = $laporan->status_id;
                                    if($status_laporan == 7){
                                        return 'Laporan telah dimasukkan dan SPPD Sudah Dibuat';
                                    }
                                    else if($status_laporan == 8){
                                        return 'Surat Diterbitkan dan SPPD Sudah Dibuat';
                                    }
                                    else if($status_laporan == 9){
                                        return 'Laporan Diterima dan SPPD Sudah Dibuat';
                                    }
                                    else if($status_laporan == 10){
                                        return 'Laporan Direview dan SPPD Sudah Dibuat';
                                    }
                                }
                            }
                        }
                    }
                ],
                ['class' => 'common\components\ToolsColumn',
                    'template' => '{view}{confirm}{reject}{view-biaya}',
                    'header' => 'Aksi',
                    'buttons' => [
                        'view' => function($url, $model){
                            return ToolsColumn::renderCustomButton($url, $model, 'Lihat Surat Tugas', 'fa fa-eye');
                        },
                        // 'confirm' => function($url, $model){
                        //     if(!empty($model->laporanAll)){
                        //         $laporan = LaporanSuratTugas::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->andWhere('deleted!=1')->one();
                        //         if($laporan->status_id == 7){
                        //             return "<li>".Html::a('<span class="fa fa-check"></span> Terima Laporan', $url, [
                        //                 'title' => Yii::t('yii', 'Terima Laporan'),
                        //                 'data-confirm' => Yii::t('yii', 'Terima laporan surat tugas?'),
                        //                 'data-method' => 'post',
                        //                  'data-pjax' => '0',
                        //             ])."</li>";
                        //         }
                        //     }
                                
                        // },
                        // 'reject' => function($url, $model){
                        //     if(!empty($model->laporanAll)){
                        //         $laporan = LaporanSuratTugas::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->andWhere('deleted!=1')->one();
                        //         if($laporan->status_id == 7){
                        //             return "<li>".Html::a('<span class="fa fa-times"></span> Tolak Laporan', $url, [
                        //                 'title' => Yii::t('yii', 'Tolak Laporan'),
                        //                 'data-confirm' => Yii::t('yii', 'Tolak laporan surat tugas?'),
                        //                 'data-method' => 'post',
                        //                  'data-pjax' => '0',
                        //             ])."</li>";
                        //         }
                        //     }
                        // },
                        'view-biaya' => function($url, $model){
                            $pegawai = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->one();
                            $bp = BiayaPerjalanan::find()->where(['surat_tugas_assignee_id' => $pegawai->surat_tugas_assignee_id])->one();
                            if(!empty($pegawai->biayaPerjalanan) && $bp->status_rencana_sekretariat == 1){
                                return ToolsColumn::renderCustomButton($url, $model, 'Lihat Biaya Perjalanan', 'fa fa-eye');
                            }
                        },
                    ],
                    'urlCreator' => function($action, $model, $key, $index){
                        if($action === 'view'){
                            return Url::toRoute(['view-wr', 'id' => $model['surat_tugas_id']]);
                        }
                        // else if($action === 'confirm'){
                        //     return Url::toRoute(['terima-laporan', 'id' => $model['surat_tugas_id']]);
                        // }
                        // else if($action === 'reject'){
                        //     return Url::toRoute(['tolak-laporan-tugas', 'id' => $model['surat_tugas_id']]);
                        // }
                        else if($action === 'view-biaya'){
                            $assigneeAll = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->all();
                            $assigneeOne = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->one();
                            $bp = BiayaPerjalanan::find()->where(['surat_tugas_assignee_id' => $assigneeOne->surat_tugas_assignee_id])->one();    

                            if (count($assigneeAll) == 1) {
                                if(!empty($assigneeOne->biayaPerjalanan) && $bp->status_rencana_sekretariat == 1){
                                    return Url::toRoute(['/sppd/biaya-perjalanan/view-wr', 'id' => $bp->biaya_perjalanan_id, 'assignee_id' => $bp->surat_tugas_assignee_id, 'surat_id' => $assigneeOne->surat_tugas_id]);
                                }
                            }
                            else{
                                $f = 0;
                                foreach ($assigneeAll as $a) {
                                    $bp2 = BiayaPerjalanan::find()->where(['surat_tugas_assignee_id' => $a->surat_tugas_assignee_id])->one();
                                    if(!empty($bp2)){
                                        $f = 1;
                                        break;
                                    }
                                }
                                if($f == 0)
                                    return Url::toRoute(['index-wr', 'id' => $model['surat_tugas_id']]);
                                else if($f == 1)
                                    return Url::toRoute(['/sppd/biaya-perjalanan/index-wr-detail', 'id' => $model->surat_tugas_id]);
                            }
                        }                        
                    }
                ],
            ],
        ]); 
        Pjax::end();
    ?>

</div>
