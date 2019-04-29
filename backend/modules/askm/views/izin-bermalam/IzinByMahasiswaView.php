<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinBermalam */

$this->title = 'Data Izin Bermalam';
$this->params['breadcrumbs'][] = ['label' => 'Izin Bermalam', 'url' => ['index-mahasiswa']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="izin-bermalam-view">

    <?php
        if ($model->status_request_id == 1) {
    ?>

    <div class="pull-right">
        Pengaturan
        <?= $uiHelper->renderButtonSet([
                'template' => ['edit', 'cancel'],
                'buttons' => [
                    'edit' => ['url' => Url::toRoute(['izin-by-mahasiswa-edit', 'id' => $model->izin_bermalam_id]), 'label'=> 'Edit Request', 'icon'=>'fa fa-pencil'],
                    'cancel' => ['url' => Url::to(['cancel-by-mahasiswa', 'id' => $model->izin_bermalam_id]), 'label'=> 'Batalkan Request', 'icon'=>'fa fa-times'],
                ],
            ]) ?>
    </div>

    <h1><?= $this->title ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?php }else if($model->status_request_id == 2){ ?>

    <div class="pull-right">
        Pengaturan
        <?= $uiHelper->renderButtonSet([
                'template' => ['print'],
                'buttons' => [
                    'print' => ['url' => Url::to(['izin-bermalam/print-ib', 'id'=>$model->izin_bermalam_id, 'action'=>"printIb"]), 'label'=> 'Print Surat IB', 'icon'=>'fa fa-print'],
                ],
            ]) ?>
    </div>

    <h1><?= $this->title ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?php }else { ?>

    <h1><?= $this->title ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?php
        }
        echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'izin_bermalam_id',
            ['label' => 'Nama Mahasiswa', 'value' => $model->dim['nama']],
            ['label' => 'NIM Mahasiswa', 'value' => $model->dim['nim']],
            // ['label' => 'Asrama', 'value' => $model->dim->['dimKamar']->kamar->asrama->name],
            [
                'attribute' => 'rencana_berangkat',
                'value' => function($model){
                    if (is_null($model->rencana_berangkat)) {
                        return '-';
                    }else{
                        return date('d M Y H:i', strtotime($model->rencana_berangkat));
                    }
                }
            ],
            [
                'attribute' => 'rencana_kembali',
                'value' => function($model){
                    if (is_null($model->rencana_kembali)) {
                        return '-';
                    }else{
                        return date('d M Y H:i', strtotime($model->rencana_kembali));
                    }
                }
            ],
            [
                'attribute' => 'realisasi_berangkat',
                'value' => function($model){
                    if (is_null($model->realisasi_berangkat)) {
                        return '-';
                    }else{
                        return date('d M Y H:i', strtotime($model->realisasi_berangkat));
                    }
                }
            ],
            [
                'attribute' => 'realisasi_kembali',
                'value' => function($model){
                    if (is_null($model->realisasi_kembali)) {
                        return '-';
                    }else{
                        return date('d M Y H:i', strtotime($model->realisasi_kembali));
                    }
                }
            ],
            ['label' => 'Keperluan IB', 'value' => $model->desc],
            ['label' => 'Tempat Tujuan IB', 'value' => $model->tujuan],
            'statusRequest.status_request',
            ['label' => $status, 'value' => function($model){
                    if (is_null($model->pegawai['nama'])) {
                        return '-';
                    }else{
                        return $model->pegawai['nama'];
                    }
                }
            ],
        ],
    ]) ?>

</div>
