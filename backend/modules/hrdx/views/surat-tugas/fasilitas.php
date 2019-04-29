<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\SuratTugas */

$uiHelper = \Yii::$app->uiHelper;

$this->title = 'Pengaturan Fasilitas';
$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas', 'url' => ['browse']];
$this->params['breadcrumbs'][] = ['label' => 'Antrian Surat Tugas', 'url' => ['antrian-surat-tugas']];
$this->params['breadcrumbs'][] = ['label' => 'Detail Surat Tugas', 'url' => ['detail-antrian','id' => $model->surat_tugas_id]];
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

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1','width' => 12, 'header'=>'Jenis Fasilitas', 'icon' => 'fa fa-gears']) ?>

    <br>
        
    <?php
        $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{error}\n{endWrapper}\n{hint}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-3',
                    'wrapper' => 'col-sm-6',
                    'error' => '',
                    'hint' => '',
                ],
            ],
            //'enableAjaxValidation'=>true,
    ]) ?>

    <table class="table table-striped table-bordered detail-view">
    <tbody>
    
        <?php
        foreach ($fasilitas_1 as $key => $value) {
        ?>
            <tr><th><?=$value->jenisFasilitas->nama?></th><td><input type="text" class="form-control" name="jenis_fasilitas[<?=$value->jenis_fasilitas_id?>]" maxlength="50" value="<?=$value->keterangan?>"></td></tr>

        <?php
        }
        ?>

        <?php
        foreach ($fasilitas_2 as $key => $value) {
        ?>

        <tr><th><?=$value->nama?></th><td><input type="text" class="form-control" name="jenis_fasilitas[<?=$value->jenis_fasilitas_id?>]" maxlength="50"></td></tr>

        <?php
        }
        ?>

        <tr><th></th><td><?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?></td></tr>

    </tbody>
    </table>

    <?php ActiveForm::end(); ?>

    <?=$uiHelper->endContentBlock()?>

<?=$uiHelper->endContentRow() ?>
