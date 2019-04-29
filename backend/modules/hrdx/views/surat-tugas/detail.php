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

    <div class="pull-right">
        <?=$uiHelper->renderButtonSet([
            'template' => ['edit','laporan'],
            'buttons' => [
                'edit' => ['url' => Url::toRoute(['edit','id'=>$model->surat_tugas_id]), 'label' => 'Ubah Surat Tugas', 'icon' => 'fa fa-edit'],
                'laporan' => ['url' => Url::toRoute(['unggah-laporan','id'=>$model->surat_tugas_id]), 'label' => 'Unggah Laporan', 'icon' => 'fa fa-upload'],
            ]
        ]) ?>
    </div>

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

<?= $uiHelper->beginContentRow() ?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1','width' => 12, 'header'=>'Laporan Penugasan', 'icon' => 'fa fa-newspaper-o']) ?>

    <?php if(!is_null($model->nama_file)){?>

    <div class="col-sm-11">
    
    <?= LinkHelper::renderLink(['options'=>'target = _blank','label'=>$model->nama_file, 'url'=>\Yii::$app->fileManager->generateUri($model->kode_file)])?>
    
    </div>

    <div class="col-sm-1">
    
    <?= Html::a('', ['hapus-laporan', 'id' => $model->surat_tugas_id], ['class'=> 'fa fa-trash']) ?>

    </div>

    <?php }?>

    <?=$uiHelper->endContentBlock()?>

<?=$uiHelper->endContentRow() ?>
