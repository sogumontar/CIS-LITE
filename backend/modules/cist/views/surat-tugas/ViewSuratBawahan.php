<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use backend\modules\cist\models\SuratTugas;
use backend\modules\cist\models\SuratTugasAssignee;
use backend\modules\sppd\models\BiayaPerjalanan;
use backend\modules\cist\models\LaporanSuratTugas;
use backend\modules\cist\models\SuratTugasFile;
use backend\modules\cist\models\Pegawai;
use backend\modules\inst\models\InstApiModel;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use common\widgets\Redactor;
use common\helpers\LinkHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\SuratTugas */

$this->title = $model->nama_kegiatan;
$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas Bawahan', 'url' => ['index-surat-bawahan']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper = \Yii::$app->uiHelper;
?>
<div class="surat-tugas-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
        if($model->status_id != 3 && $model->status_id != 5 && $model->status_id != 4){
            if($model->status_id != 6){
                echo Html::a('Terima', ['terima',  'id' => $model->surat_tugas_id], [
                'class' => 'btn btn-success',
                'data-method' => 'POST',
                'data' => [
                    'confirm' => 'Terima permohonan surat tugas?',
                ],
            ]) . " ";
            }
            
            //Review Modal
            Modal::begin([
                'header' => '<h2>Tolak Surat Tugas</h2>',
                'toggleButton' => ['label' => 'Tolak', 'class' => 'btn btn-danger'],
            ]);
            $form = ActiveForm::begin(['action' => \yii\helpers\Url::to(['tolak', 'id' => $model->surat_tugas_id])]);
            echo $form->field($model, 'review_surat')->widget(Redactor::className(), ['options' => [
                'minHeight' => 100,
            ], ]);
            
            echo Html::submitButton('Kirim', ['class' => 'btn btn-warning']);
            ActiveForm::end();
            Modal::end();
        }
        if($model['status_id'] == 3){
            echo Html::a('Lihat Surat Tugas', ['create-pdf', 'id' => $model->surat_tugas_id], ['class' => 'btn btn-primary','data-method' => 'POST',
            ]) . " ";
            echo "&nbsp";

        }
        $pegawai = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->one();
        if(!empty($pegawai->biayaPerjalanan)){
            $assigneeAll = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->all();
            $assigneeOne = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->one();   

            if (count($assigneeAll) == 1) {
                $bp = BiayaPerjalanan::find()->where(['surat_tugas_assignee_id' => $assigneeOne->surat_tugas_assignee_id])->one(); 
                if(!empty($assigneeOne->biayaPerjalanan) && $bp->status_rencana_sekretariat == 1){
                    echo Html::a('Lihat Biaya Perjalanan', ['/sppd/biaya-perjalanan/view-atasan', 'id' => $bp->biaya_perjalanan_id, 'assignee_id' => $bp->surat_tugas_assignee_id, 'surat_id' => $assigneeOne->surat_tugas_id], ['class' => 'btn btn-warning']) . " ";
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
                    echo Html::a('Lihat Biaya Perjalanan', ['index-surat-bawahan', 'id' => $model['surat_tugas_id']], ['class' => 'btn btn-warning']) . " ";
                else if($f == 1)
                    echo Html::a('Lihat Biaya Perjalanan', ['/sppd/biaya-perjalanan/index-atasan-detail', 'id' => $model->surat_tugas_id], ['class' => 'btn btn-warning']) . " ";
            }                          
        }
    ?>

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
</div>
