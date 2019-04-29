<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\DetailView;
use backend\modules\cist\models\Pegawai;
use backend\modules\cist\models\Status;
use backend\modules\cist\models\SuratTugas;
use backend\modules\cist\models\SuratTugasAssignee;
use backend\modules\sppd\models\BiayaPerjalanan;
use backend\modules\cist\models\SuratTugasFile;
use backend\modules\cist\models\LaporanSuratTugas;
use backend\modules\inst\models\InstApiModel;
use kartik\datetime\DateTimePicker;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use common\widgets\Redactor;
use common\helpers\LinkHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\SuratTugas */

$this->title = $model->nama_kegiatan;
$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas', 'url' => ['index-pegawai']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper = \Yii::$app->uiHelper;
?>
<div class="surat-tugas-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if($modelLaporan != null){
            $model_laporan = LaporanSuratTugas::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->andWhere('deleted!=1')->one();
            if($model_laporan->status_id == 10){
                \Yii::$app->messenger->addWarningFlash("Laporan direview. Harap submit laporan yang telah diperbaiki.");
            }                
        }


        $link = ($model->jenis_surat_id == 1) ? 'edit-luar-kampus' : 'edit-dalam-kampus';   
        if($model['status_id'] == 3){
            echo Html::a('Lihat Surat Tugas', ['create-pdf', 'id' => $model->surat_tugas_id], ['class' => 'btn btn-primary','data-method' => 'POST',
            ]) . " ";
            echo "&nbsp";

        }
        if($modelLaporan != null){
            $label = (SuratTugas::getLaporan($model->surat_tugas_id)->nama_file == null) ? 'Submit Laporan' : 'Ubah Laporan';

            if(SuratTugas::getLaporan($model->surat_tugas_id)->status_id == 8 || SuratTugas::getLaporan($model->surat_tugas_id)->status_id == 10){   //If Surat Tugas Status is Diterbitkan
                Modal::begin([
                    'header' => '<h2>Submit Laporan Tugas</h2>',
                    'toggleButton' => ['label' => $label, 'class' => 'btn btn-success'],
                ]);

                $form = ActiveForm::begin(['action' => \yii\helpers\Url::to(['submit-laporan', 'id' => $model->surat_tugas_id])]);

                echo $form->field($model, 'realisasi_berangkat')->widget(DateTimePicker::className(),
                [
                    'options' => ['placeholder' => 'Pilih tanggal dan waktu'],
                    'pluginOptions' => [
                        'autoclose' => 'true',
                        'todayHighlight' => true
                    ]
                ]);
            
                echo $form->field($model, 'realisasi_kembali')->widget(DateTimePicker::className(),
                [
                    'options' => ['placeholder' => 'Pilih tanggal dan waktu'],
                    'pluginOptions' => [
                        'autoclose' => 'true',
                        'todayHighlight' => true
                    ]
                ]);

                echo $form->field($model, 'files[]')->fileInput(['multiple' => true], ['required' => true]);
                echo Html::submitButton('Submit', ['class' => 'btn btn-success']);

                ActiveForm::end();
                
                Modal::end();
            }
        } 
        
        
        if($model->status_id == 1 || $model->status_id == 2){   //If Surat Tugas status is Memohon or Review
            echo Html::a('Ubah', [$link, 'id' => $model->surat_tugas_id], ['class' => 'btn btn-warning']) . " ";
            echo Html::a('Batalkan Pengajuan', ['batalkan', 'id' => $model->surat_tugas_id], ['class' => 'btn btn-danger',
                'data-method' => 'POST',
                'data' => [
                    'confirm' => 'Batalkan Pengajuan Surat Tugas?',
                ],]) . " ";
        }

        $pegawai = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->one();
        if(!empty($pegawai->biayaPerjalanan)){
            $bp = BiayaPerjalanan::find()->where(['surat_tugas_assignee_id' => $pegawai->surat_tugas_assignee_id])->one();
            if($bp->status_rencana_sekretariat == 1){
                $pegawais = Pegawai::find()->where(['user_id' => Yii::$app->user->identity->id])->andWhere('deleted!=1')->one();
                $pegawai = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->andWhere(['id_pegawai' => $pegawais->pegawai_id])->one();
                if(!empty($pegawai->biayaPerjalanan) ){
                    $bp = BiayaPerjalanan::find()->where(['surat_tugas_assignee_id' => $pegawai->surat_tugas_assignee_id])->one();
                    if($bp->status_rencana_keuangan != 2 || $bp->status_realisasi_dana == 1){
                        echo Html::a('Lihat Biaya Perjalanan', ['/sppd/biaya-perjalanan/view-pemohon', 'id' => $bp->biaya_perjalanan_id], ['class' => 'btn btn-warning']) . " ";
                    }
                    else{
                        echo Html::a('Lihat Biaya Perjalanan', ['/sppd/biaya-perjalanan/add-biaya-pemohon', 'id' => $pegawai->surat_tugas_assignee_id], ['class' => 'btn btn-warning']) . " ";
                    }                            
                }
            }                            
        }
        ?>
    </p>
<?= $uiHelper->beginContentRow() ?>
<?= $uiHelper->beginContentBlock(['id' => 'grid-system2',
                'width' => 6, ]) ?>
    
    <?= DetailView::widget([
        'model' => $model,
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '-',
        ],
        'attributes' => [
            [
                'attribute' => 'perequest0.nama',
                'label' => 'Pemohon',
            ],
            [
                'label' => 'Peserta',
                'value' => function($model){
                    $pegawais = SuratTugas::getAssignee($model->surat_tugas_id);
                    return '- '.implode('<br/>- ', array_column($pegawais, 'nama'));
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'statusName.name',
                'label' => 'Status',
            ],
            [
                'attribute' => 'penyetuju0.nama',
                'label' => 'Penyetuju/Penolak/Reviewer',
            ],
            [
                'attribute' => 'review_surat',
                'format' => 'html',
            ],
            [
                'attribute' => 'no_surat',
                'label' => 'Nomor Surat',
            ],
            'nama_kegiatan',
            'agenda',
            'tempat',
            [
                'attribute' => 'tanggal_berangkat',
                'format' => 'raw',
                'value' => function($model){
                    if(is_null($model->tanggal_berangkat)){
                        return '-';
                    }
                    else{
                        return date('d M Y H:i', strtotime($model->tanggal_berangkat));
                    }
                }
            ],
            [
                'attribute' => 'tanggal_kembali',
                'format' => 'raw',
                'value' => function($model){
                    if(is_null($model->tanggal_kembali)){
                        return '-';
                    }
                    else{
                        return date('d M Y H:i', strtotime($model->tanggal_kembali));
                    }
                }
            ],
            [
                'attribute' => 'tanggal_mulai',
                'format' => 'raw',
                'value' => function($model){
                    if(is_null($model->tanggal_mulai)){
                        return '-';
                    }
                    else{
                        return date('d M Y H:i', strtotime($model->tanggal_mulai));
                    }
                }
            ],
            [
                'attribute' => 'tanggal_selesai',
                'format' => 'raw',
                'value' => function($model){
                    if(is_null($model->tanggal_selesai)){
                        return '-';
                    }
                    else{
                        return date('d M Y H:i', strtotime($model->tanggal_selesai));
                    }
                }
            ],
        ],
    ]) ?>

    <?= $uiHelper->endContentBlock() ?>
    <?= $uiHelper->beginContentBlock(['id' => 'grid-system3',
                'width' => 6, ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '-',
        ],
        'attributes' => [
            
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
                'attribute' => 'desc_surat_tugas',
                'format' => 'html',
            ],
            [
                'attribute' => 'transportasi',
                'format' => 'html',
            ],
            [
                'attribute' => 'pengalihan_tugas',
                'format' => 'html',
            ],
            [
                'label' => 'Lampiran',
                'value' => function($model){
                    $result = null;
                    $modelFile = SuratTugasFile::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->all();
                    foreach($modelFile as $data){
                        //$result .= Html::a($data['nama_file'], ['download-attachments', 'id' => $data['surat_tugas_file_id']]) . "<br/>";
                        $result .= LinkHelper::renderLink(['options'=>'target = _blank', 'label'=>$data['nama_file'], 'url'=>\Yii::$app->fileManager->generateUri($data['kode_file'])]) . "<br/>";
                    }

                    return $result;
                },
                'format' => 'html',
            ],
            [
                'label' => 'Atasan',
                'value' => function($model){
                    $pegawais = SuratTugas::getAtasan($model->surat_tugas_id);
                    return '- '.implode('<br/>- ', array_column($pegawais, 'nama'));
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'review_surat',
                'format' => 'html',
            ],
            [
                'label' => 'Batas Submission',
                'value' => function($model){
                    $result = null;
                    $modelLaporan = $modelLaporan = LaporanSuratTugas::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->one();
                    if(is_null($modelLaporan)){
                        return '-';
                    }
                    else{
                        return date('d M Y H:i', strtotime($modelLaporan->batas_submit));
                    }
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'catatan',
                'format' => 'html',
            ],
            [
                'label' => 'Status Laporan',
                'value' => function($model){
                    $result = null;
                    $modelLaporan = $modelLaporan = LaporanSuratTugas::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->all();
                    foreach($modelLaporan as $data){
                        if(SuratTugas::getStatus($data->status_id) != null){
                            $status = $data->status_id;
                            $result .= SuratTugas::getStatus($data->status_id) . "<br/>";
                        }
                    }
                    
                    return $result;
                },
                'format' => 'html',
            ],
            [
                'label' => 'Laporan',
                'value' => function($model){
                    $result = null;
                    $modelLaporan = $modelLaporan = LaporanSuratTugas::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->all();
                    foreach($modelLaporan as $data){
                        if($data->nama_file != null){
                            //$result .= Html::a($data->nama_file, ['download-reports', 'id' => $data->laporan_surat_tugas_id]) . "<br/>";
                            $result .= LinkHelper::renderLink(['options'=>'target = _blank', 'label'=>$data->nama_file, 'url'=>\Yii::$app->fileManager->generateUri($data->kode_laporan)]) . "<br/>";
                        }
                    }
                    
                    return $result;
                },
                'format' => 'html',
            ],
        ],
    ]) ?>

    <?= $uiHelper->endContentBlock() ?>
    <?= $uiHelper->endContentRow() ?>

    <!-- Untuk list lampiran -->
    <?php
        // if($modelFile != null){
        //     $idx = 1;
        //     echo "<b>List Lampiran</b>:<br/>"; 
        //     foreach($modelFile as $data){
        //         // echo $idx . ". " . LinkHelper::renderLink(['options'=>'target = _blank', 'label'=>$data->nama_file, 'url'=>\Yii::$app->fileManager->generateUri($data->kode_file)]) . "<br/>";
        //         echo $idx . ". " . Html::a($data->nama_file, ['download-attachments', 'id' => $data->surat_tugas_file_id]) . "<br/>";
        //     }
        //     echo "<br/>";
        // }
    ?>

    <?php
        if($modelLaporan != null){  //If there are Laporan in this Surat Tugas
            // foreach($modelLaporan as $data){
            //     if($data->nama_file != null){
            //         echo "<b>Laporan</b>:<br/>";
            //         echo Html::a($data->nama_file, ['download-reports', 'id' => $data->laporan_surat_tugas_id]) . "<br/><br/>";
            //     }
            //     echo "<b>Batas Submission</b>:<br/>"; 
            //     echo $data->batas_submit . "<br/><br/>";
            //     if(SuratTugas::getStatus($data->status_id) != null){
            //         $status = $data->status_id;
            //         echo "<b>Status Laporan</b>:<br/>"; 
            //         echo SuratTugas::getStatus($data->status_id) . "<br/>";
            //     }
            // }
        }
    ?>
    
    <br/>   

</div>
