<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yii\widgets\DetailView;
use backend\modules\cist\models\Pegawai;
use backend\modules\cist\models\LaporanSuratTugas;
use yii\helpers\ArrayHelper;
use backend\modules\cist\models\SuratTugas;
use backend\modules\cist\models\StrukturJabatan;
use backend\modules\cist\models\SuratTugasFile;
use backend\modules\cist\models\Status;
use backend\modules\inst\models\InstApiModel;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use common\widgets\Redactor;
use kartik\datetime\DateTimePicker;
use common\helpers\LinkHelper;
use backend\modules\cist\models\SuratTugasAssignee;
use backend\modules\sppd\models\BiayaPerjalanan;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\SuratTugas */

$this->title = $model->nama_kegiatan;
$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas', 'url' => ['index-hrd']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper = \Yii::$app->uiHelper;
?>
<div class="surat-tugas-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php    
        if($model->status_id == 6){   //If Surat Tugas status is diterima
            $month = convertToRome(date('n'));
            $year = date('y');

            $nomor = $model->surat_tugas_id;
            $n = '';  
            if($nomor < 10){
                $n = '00'.$nomor.'';
            }  
            else if($nomor < 100){
                $n = '0'.$nomor.'';
            }
            else if($nomor >= 100){
                $n = $nomor;
            }
            //Terbitkan Modal
            Modal::begin([
                'header' => '<h2>Terbitkan Surat Tugas</h2>',
                'toggleButton' => ['label' => 'Terbitkan', 'class' => 'btn btn-success'],
            ]);

            // $form = ActiveForm::begin(['action' => 'terbitkan?id=' . $model->surat_tugas_id]);
            $form = ActiveForm::begin(['action' => \yii\helpers\Url::to(['terbitkan', 'id' => $model->surat_tugas_id])]);

            echo $form->field($model, 'no_surat')->textInput(['maxlength' => true, 'value' => $n.'/ITDel/WR2/SDM/ST/'.$month.'/'.$year]);
            $model->penandatangan = 84;

            echo $form->field($model, 'penandatangan')->dropdownList(ArrayHelper::map(StrukturJabatan::find()->where(['deleted' => 0])->all(),'struktur_jabatan_id' , 'jabatan'),
                  [
                    'prompt' => 'Pilih Penandatangan',
                    'required' =>true,
                  ]);
            
            echo Html::submitButton('Terbitkan', ['class' => 'btn btn-success']);
            
            ActiveForm::end();

            Modal::end();

            //Tambah Catatan
            Modal::begin([
                'header' => '<h2>Catatan Tambahan</h2>',
                'toggleButton' => ['label' => 'Tambah Catatan di Surat Tugas', 'class' => 'btn btn-warning'],
            ]);

            // $form = ActiveForm::begin(['action' => 'add-catatan?id=' . $model->surat_tugas_id]);
            $form = ActiveForm::begin(['action' => \yii\helpers\Url::to(['add-catatan', 'id' => $model->surat_tugas_id])]);

            echo $form->field($model, 'catatan')->widget(Redactor::className(), ['options' => [
                'minHeight' => 100,
            ], ]);
            
            echo Html::submitButton('Tambah', ['class' => 'btn btn-warning']);
            
            ActiveForm::end();

            Modal::end();

            //Tambah Keterangan
            Modal::begin([
                'header' => '<h2>Keterangan Tambahan</h2>',
                'toggleButton' => ['label' => 'Tambah Keterangan', 'class' => 'btn btn-warning'],
            ]);

            // $form = ActiveForm::begin(['action' => 'add-keterangan?id=' . $model->surat_tugas_id]);
            $form = ActiveForm::begin(['action' => \yii\helpers\Url::to(['add-keterangan', 'id' => $model->surat_tugas_id])]);

            echo $form->field($model, 'desc_surat_tugas')->widget(Redactor::className(), ['options' => [
                'minHeight' => 100,
            ], ]);
            
            echo Html::submitButton('Tambah', ['class' => 'btn btn-warning']);
            
            ActiveForm::end();

            Modal::end();
        }

        $modelLaporan = SuratTugas::getLaporan($model->surat_tugas_id);
        if($modelLaporan != null){
            //Ubah Batas Submission Modal
            Modal::begin([
                'header' => '<h2>Edit Batas Submission Laporan</h2>',
                'toggleButton' => ['label' => 'Ubah Batas Submission', 'class' => 'btn btn-warning'],
            ]);

            // $form = ActiveForm::begin(['action' => 'edit-submission?id=' . $model->surat_tugas_id]);
            $form = ActiveForm::begin(['action' => \yii\helpers\Url::to(['edit-submission', 'id' => $model->surat_tugas_id])]);

            echo "<label>Batas Submission</label>";
            echo DateTimePicker::widget([
                'model' => $modelLaporan,
                'attribute' => 'batas_submit',
                'options' => ['placeholder' => 'Pilih tanggal dan waktu'],
                'pluginOptions' => [
                    'autoclose' => 'true',
                    'todayHighlight' => true
                ]
            ]) . "<br/>";
            
            echo Html::submitButton('Ubah', ['class' => 'btn btn-warning']);

            ActiveForm::end();
            
            Modal::end();
        }

        if($model->status_id == 3){
            echo Html::a('Lihat Surat Tugas', ['create-pdf', 'id' => $model->surat_tugas_id], [
                'class' => 'btn btn-primary',
                'data-method' => 'POST',
            ]) . " ";

            echo "&nbsp";
        }

        $assigneeAll = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->all();
        $assigneeOne = SuratTugasAssignee::find()->where(['surat_tugas_id' => $model->surat_tugas_id])->one();
        
        if(!empty($assigneeOne->biayaPerjalanan)){  
            if (count($assigneeAll) == 1) {
                if(!empty($assigneeOne->biayaPerjalanan)){
                    $bp = BiayaPerjalanan::find()->where(['surat_tugas_assignee_id' => $assigneeOne->surat_tugas_assignee_id])->one();   
                    if($bp->status_rencana_sekretariat == 1){
                        echo Html::a('Lihat Biaya Perjalanan', ['/sppd/biaya-perjalanan/view-hrd', 'id' => $bp->biaya_perjalanan_id, 'assignee_id' => $bp->surat_tugas_assignee_id, 'surat_id' => $assigneeOne->surat_tugas_id], ['class' => 'btn btn-primary','data-method' => 'POST', ]) . " ";
                    }
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
                    echo Html::a('Lihat Biaya Perjalanan', ['index-hrd', 'id' => $model->surat_tugas_id], ['class' => 'btn btn-primary','data-method' => 'POST', ]) . " ";
                else if($f == 1)
                    echo Html::a('Lihat Biaya Perjalanan', ['/sppd/biaya-perjalanan/index-hrd-detail', 'id' => $model->surat_tugas_id], ['class' => 'btn btn-primary','data-method' => 'POST', ]) . " ";
            }
        }
        echo "&nbsp";
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
    
</div>
<?php
    function convertToRome($month){
        switch ($month) {
            case 1:
                return 'I';
                break;
            case 2:
                return 'II';
                break;
            case 3:
                return 'III';
                break;
            case 4:
                return 'IV';
                break;
            case 5:
                return 'V';
                break;
            case 6:
                return 'VI';
                break;
            case 7:
                return 'VII';
                break;
            case 8:
                return 'VIII';
                break;
            case 9:
                return 'IX';
                break;
            case 10:
                return 'X';
                break;
            case 11:
                return 'XI';
                break;
            case 12:
                return 'XII';
                break;
        }
    }
?>