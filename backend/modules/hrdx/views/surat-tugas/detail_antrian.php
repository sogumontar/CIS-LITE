<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use common\helpers\LinkHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\SuratTugas */

$uiHelper = \Yii::$app->uiHelper;

$this->title = 'Detail Surat Tugas';
$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas', 'url' => ['browse']];
$this->params['breadcrumbs'][] = ['label' => 'Antrian Surat Tugas', 'url' => ['antrian-surat-tugas']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>

<style type="text/css">
    table.detail-view th {
        width: 25%;
    }

    table.detail-view td {
        width: 75%;
    }
</style>

<?= $uiHelper->beginContentRow() ?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1','width' => 12,]) ?>

    <?php
        if (isset($user_role)) {
            if ($user_role['role']['name']=='wr2') {
                if ($model->status==0) {
                
    ?>

    <div class="pull-left">
        <a href="<?=Url::toRoute(['terima-surat-tugas','id'=>$model->surat_tugas_id])?>" class="btn btn-success">Terima</a>
        <br>
        <br>
    </div>

    <?php
                }
            }else if ($user_role['role']['name']=='hrd') {

    ?>

    <div class="pull-right">
        <?=$uiHelper->renderButtonSet([
            'template' => ['fasilitas','print'],
            'buttons' => [
                'fasilitas' => ['url' => Url::toRoute(['atur-fasilitas-perjalanan','id'=>$model->surat_tugas_id]), 'label' => 'Atur Fasilitas Perjalanan', 'icon' => 'fa fa-car'],
                'print' => ['url' => Url::toRoute(['cetak-surat-tugas','id'=>$model->surat_tugas_id]), 'label' => 'Cetak Surat Tugas', 'icon' => 'fa fa-print'],
            ]
        ]) ?>
    </div>

    <?php

            }
        }
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'no_surat_tugas',
            'tugas',
            'keterangan',
            [
                'attribute' => 'Pemberi Tugas',
                'format' =>'raw',
                'value' =>call_user_func(function() use($pemberi_tugas){
                    return $pemberi_tugas->pegawai->nama;
                })
            ],
            [
                'attribute'=>'Penerima Tugas',
                'format' => 'raw',
                'value'=>call_user_func(function() use($penerima_tugas){
                    $result='';
                    foreach ($penerima_tugas as $key => $value) {
                        $result=$result.' '.$value->pegawai->nama.',';
                    }

                    if($result!=''){
                        $result=rtrim($result,',');
                    }

                    return $result;
                }),
            ],
            'kota_tujuan',
            'lokasi_tugas',
            'tanggal_berangkat',
            'tanggal_kembali',
            'tanggal_mulai',
            'tanggal_selesai',
            'keterangan:ntext',
            'catatan',
            [
                'attribute' => 'Status',
                'format' => 'raw',
                'value' => call_user_func(function() use($model){
                    return $model->status==0?'Baru':($model->status==1?'Diterima':'Ditolak');
                })
            ],
        ],
    ]) ?>

    <?=$uiHelper->endContentBlock()?>

<?=$uiHelper->endContentRow() ?>

<?= $uiHelper->beginContentRow() ?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1','width' => 12, 'header'=>'Fasilitas Perjalanan', 'icon' => 'fa fa-gears']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'jenisFasilitas.nama',
            'keterangan',
        ],
    ]) ?>

    <?=$uiHelper->endContentBlock()?>

<?=$uiHelper->endContentRow() ?>
