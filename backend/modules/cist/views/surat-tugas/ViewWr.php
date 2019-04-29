<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\DetailView;
use backend\modules\cist\models\Pegawai;
use backend\modules\cist\models\LaporanSuratTugas;
use backend\modules\cist\models\Status;
use backend\modules\cist\models\SuratTugas;
use backend\modules\cist\models\SuratTugasFile;
use backend\modules\inst\models\InstApiModel;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use common\widgets\Redactor;
use kartik\datetime\DateTimePicker;
use common\helpers\LinkHelper;
use backend\modules\cist\models\SuratTugasAssignee;
use backend\modules\sppd\models\BiayaPerjalanan;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\SuratTugas */

$this->title = $model->nama_kegiatan;
$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas', 'url' => ['index-wr']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper = \Yii::$app->uiHelper;
?>
<div class="surat-tugas-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
        if($modelLaporan != null){  //If there are Laporan in this Surat Tugas
            foreach($modelLaporan as $data){
                if(SuratTugas::getStatus($data->status_id) != null){
                    $status = $data->status_id;
                }
            }
        }
    ?>

    <p>
        <?php
            if($modelLaporan != null && $status == 7){
                echo Html::a('Terima Laporan', ['terima-laporan', 'id' => $model->surat_tugas_id], [
                    'class' => 'btn btn-success',
                    'data-method' => 'post',
                    'data' => [
                        'confirm' => 'Terima laporan surat tugas?',
                    ],
                ]) . " ";

                Modal::begin([
                    'header' => '<h2>Review Laporan</h2>',
                    'toggleButton' => ['label' => 'Review Laporan', 'class' => 'btn btn-warning'],
                ]);

                $form = ActiveForm::begin(['action' => \yii\helpers\Url::to(['review-laporan', 'id' => $model->surat_tugas_id])]);

                echo $form->field($model, 'review_laporan')->widget(Redactor::className(), ['options' => [
                    'minHeight' => 100,
                ], ]);
                echo Html::submitButton('Submit', ['class' => 'btn btn-success']);

                ActiveForm::end();
                
                Modal::end();
            }

            if($model->status_id == 3){
                echo Html::a('Lihat Surat Tugas', ['create-pdf', 'id' => $model->surat_tugas_id], [
                    'class' => 'btn btn-primary',
                    'data-method' => 'POST',
                ]) . " ";
            }

            $assigneeAll = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->all();
            $assigneeOne = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->one();
            if(!empty($assigneeOne->biayaPerjalanan)){
                $bp = BiayaPerjalanan::find()->where(['surat_tugas_assignee_id' => $assigneeOne->surat_tugas_assignee_id])->one();    

                if (count($assigneeAll) == 1) {
                    if(!empty($assigneeOne->biayaPerjalanan) && $bp->status_rencana_sekretariat == 1){
                        echo Html::a('Lihat Biaya Perjalanan', ['/sppd/biaya-perjalanan/view-wr', 'id' => $bp->biaya_perjalanan_id, 'assignee_id' => $bp->surat_tugas_assignee_id, 'surat_id' => $assigneeOne->surat_tugas_id], ['class' => 'btn btn-warning','data-method' => 'POST', ]) . " ";
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
                        echo Html::a('Lihat Biaya Perjalanan', ['index-wr', 'id' => $model['surat_tugas_id']], ['class' => 'btn btn-primary','data-method' => 'POST', ]) . " ";
                    else if($f == 1)
                        echo Html::a('Lihat Biaya Perjalanan', ['/sppd/biaya-perjalanan/index-wr-detail', 'id' => $model->surat_tugas_id], ['class' => 'btn btn-primary','data-method' => 'POST', ]) . " ";
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
                'label' => 'Nomor Surat',
                'attribute' => 'no_surat'
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
                'label' => 'Peserta',
                'value' => function($model){
                    $pegawais = SuratTugas::getAssignee($model->surat_tugas_id);
                    return '- '.implode('<br/>- ', array_column($pegawais, 'nama'));
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
                'attribute' => 'pengalihan_tugas',
                'format' => 'html',
            ],
            [
                'attribute' => 'transportasi',
                'format' => 'html',
            ],
            [
                'attribute' => 'review_surat',
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
                    $modelLaporan = LaporanSuratTugas::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->all();
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
                'label' => 'Review Laporan',
                'value' => function($model){
                    $result = null;
                    $modelLaporan = LaporanSuratTugas::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->one();
                    $result .= $modelLaporan->review_laporan . "<br/>";
                    
                    return $result;
                },
                'format' => 'html',
            ]
        ],
    ]) ?>

    <?= $uiHelper->endContentBlock() ?>
    <?= $uiHelper->endContentRow() ?>    
    
</div>
