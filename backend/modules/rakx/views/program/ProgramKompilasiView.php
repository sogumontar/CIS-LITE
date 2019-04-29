<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use backend\modules\rakx\models\SumberDana;
use backend\modules\rakx\models\Program;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\Program */

$this->title = $model->kode_program.' '.$model->name;
$this->title = (strlen($this->title)>100?(substr($this->title,0,100).'...'):$this->title);
$this->params['breadcrumbs'][] = ['label' => 'Kompilasi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

$uiHelper = \Yii::$app->uiHelper;
$valid = Program::isProgramValid($model->program_id)
?>

<div class="program-view">

    <?php
        $uiHelper->beginTab([
            'tabs' => [
                ['id' => 'data_program', 'label' => 'Data Program', 'isActive' => $tab_program],
                ['id' => 'data_detil', 'label' => 'Breakdown ('.$count_detil.')', 'isActive' => $tab_detil],
                ['id' => 'data_dana', 'label' => 'Sumber Dana', 'isActive' => $tab_dana],
                ['id' => 'data_review', 'label' => 'Review ('.$count_review.')', 'isActive' => $tab_review],
                ['id' => 'data_status', 'label' => 'Status & Riwayat', 'isActive' => $tab_status],
            ]
        ]);
    ?>

    <?= $uiHelper->beginTabContent(['id'=>'data_program', 'isActive' => $tab_program]) ?>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'value' => $model->strukturJabatanHasMataAnggaran->tahunAnggaran->tahun,
                    'label' => 'Tahun Anggaran',
                ],
                [
                    'value' => $model->strukturJabatanHasMataAnggaran->strukturJabatan->jabatan,
                    'label' => 'Jabatan',
                ],
                [
                    'value' => $model->strukturJabatanHasMataAnggaran->mataAnggaran->name,
                    'label' => 'Mata Anggaran',
                ],
                [
                    'attribute' => 'rencana_strategis_id',
                    'value' => isset($model->rencanaStrategis->name)?$model->rencanaStrategis->name:'-',
                    'label' => 'Rencana Strategis',
                    'format' => 'html',
                ],
                'kode_program',
                'name:ntext',
                [
                    'attribute' => 'tujuan',
                    'format' => 'html',
                ],
                [
                    'attribute' => 'sasaran',
                    'format' => 'html',
                ],
                [
                    'attribute' => 'target',
                    'format' => 'html',
                ],
                [
                    'attribute' => 'desc',
                    'format' => 'html',
                ],
                [
                    'attribute' => 'waktu',
                    'label' => 'Waktu',
                    'value' => function($model){
                        $waktu = '';
                        $awal = true;
                        foreach($model->programHasWaktus as $w){
                            if($w->deleted!=1){
                                if(!$awal){
                                    $waktu .= ', '.$w->bulan->name;
                                }else{
                                    $waktu .= $w->bulan->name;
                                    $awal = false;
                                }
                            }
                        }
                        return $waktu!=''?$waktu:'-';
                    },
                ],
                'volume',
                [
                    'attribute' => 'satuan_id',
                    'value' => $model->satuan->name,
                    'label' => 'Satuan',
                ],
                [
                    'attribute' => 'harga_satuan',
                    'value' => function($model){
                        return "Rp".number_format($model->harga_satuan,2,',','.');
                    },
                ],
                [
                    'attribute' => 'jumlah_sebelum_revisi',
                    'value' => function($model){
                        return "Rp".number_format($model->jumlah_sebelum_revisi,2,',','.');
                    },
                ],
                [
                    'attribute' => 'jumlah',
                    'value' => function($model){
                        return "Rp".number_format($model->jumlah,2,',','.');
                    },
                ],
            ],
        ]) ?>

        <?= $uiHelper->endTabContent() ?>

        <?= $uiHelper->beginTabContent(['id'=>'data_detil', 'isActive' => $tab_detil]) ?>
        <?= $this->render('_detilProgramKompilasi', [
                'dataProviderDetil' => $dataProviderDetil,
                'searchModelDetil' => $searchModelDetil,
                'program' => $model,
            ])
        ?>
        <?= $uiHelper->endTabContent() ?>

        <?= $uiHelper->beginTabContent(['id'=>'data_dana', 'isActive' => $tab_dana]) ?>
        <?= $this->render('_sumberDanaKompilasi', [
                'dataProviderDana' => $dataProviderDana,
                'searchModelDana' => $searchModelDana,
                'program' => $model,
            ])
        ?>
        <?= $uiHelper->endTabContent() ?>

        <?= $uiHelper->beginTabContent(['id'=>'data_review', 'isActive' => $tab_review]) ?>
        <?= $this->render('_reviewKompilasi', [
                //'dataProviderReview' => $dataProviderReview,
                //'searchModelReview' => $searchModelReview,
                'review_program' => $review_program,
                'model_review' => $model_review,
                'program' => $model,
                //'pejabat_id' => $pejabat_id,
                //'program' => $model,
                //'pejabat' => $pejabat,
            ])
        ?>
        <?= $uiHelper->endTabContent() ?>


        <?= $uiHelper->beginTabContent(['id'=>'data_status', 'isActive' => $tab_status]) ?>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'status_program_id',
                    'value' => !isset($model->statusProgram->name)?'-':($model->status_program_id!=0 && $model->status_program_id!=6?$model->statusProgram->name:$model->statusProgram->name.' <a href="#" data-toggle="tooltip" data-placement="top" title="'.\yii\helpers\HtmlPurifier::process($valid[0]).'"><i class="fa fa-info-circle"></i></a>'),
                    'format' => 'html',
                ],
                [
                    'attribute' => 'diusulkan_oleh',
                    'value' => isset($model->diusulkan_oleh)?$model->diusulkanOleh->pegawai->nama.' - '.$model->diusulkanOleh->strukturJabatan->jabatan:'-',
                ],
                [
                    'attribute' => 'tanggal_diusulkan',
                    'value' => isset($model->tanggal_diusulkan)?(date('d M Y', strtotime($model->tanggal_diusulkan))):'-',
                ],
                [
                    'attribute' => 'disetujui_oleh',
                    'value' => isset($model->disetujui_oleh)?$model->disetujuiOleh->pegawai->nama.' - '.$model->disetujuiOleh->strukturJabatan->jabatan:'-',
                ],
                [
                    'attribute' => 'tanggal_disetujui',
                    'value' => isset($model->tanggal_disetujui)?(date('d M Y', strtotime($model->tanggal_disetujui))):'-',
                ],
                [
                    'attribute' => 'is_revisi',
                    'value' => $model->is_revisi==1?'Ya':'Tidak',
                ],
                [
                    'attribute' => 'direvisi_oleh',
                    'value' => isset($model->direvisi_oleh)?$model->direvisiOleh->pegawai->nama.' - '.$model->direvisiOleh->strukturJabatan->jabatan:'-',
                ],
                [
                    'attribute' => 'tanggal_direvisi',
                    'value' => isset($model->tanggal_direvisi)?(date('d M Y', strtotime($model->tanggal_direvisi))):'-',
                ],
                [
                    'attribute' => 'dilaksanakan_oleh',
                    'value' => isset($model->dilaksanakan_oleh)?$model->dilaksanakanOleh->jabatan.''.(isset($model->dilaksanakanOleh->unit->name)?' - '.$model->strukturJabatan->unit->name:''):'-',
                ],
                [
                    'attribute' => 'ditolak_oleh',
                    'value' => isset($model->ditolak_oleh)?$model->ditolakOleh->pegawai->nama.' - '.$model->ditolakOleh->strukturJabatan->jabatan:'-',
                ],
                [
                    'attribute' => 'tanggal_ditolak',
                    'value' => isset($model->tanggal_ditolak)?(date('d M Y', strtotime($model->tanggal_ditolak))):'-',
                ],
            ],
        ]) ?>

        <?= $uiHelper->endTabContent() ?>
</div>
