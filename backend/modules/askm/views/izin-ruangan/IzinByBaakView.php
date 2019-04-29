<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\modules\askm\models\Pegawai;
/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinPenggunaanRuangan */

$this->title = 'Detail Izin';
$this->params['breadcrumbs'][] = ['label' => 'Izin Penggunaan Ruangan', 'url' => ['index-baak']];
$this->params['breadcrumbs'][] = ['label' => 'Daftar Izin', 'url' => ['izin-by-baak-index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
$baak = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
?>
<div class="izin-ruangan-view">

    <?php
        if ($model->status_request_id == 1) {
    ?>

    <div class="pull-right">
        Pengaturan
        <?= $uiHelper->renderButtonSet([
                'template' => ['approve', 'reject', 'edit'],
                'buttons' => [
                    'approve' => ['url' => Url::toRoute(['approve-by-baak', 'id' => $model->izin_ruangan_id, 'id_baak' => $baak->pegawai_id]), 'label'=> 'Setujui Request', 'icon'=>'fa fa-check'], // id keasramaan diambil saat sudah login
                    'reject' => ['url' => Url::toRoute(['reject-by-baak', 'id' => $model->izin_ruangan_id, 'id_baak' => $baak->pegawai_id]), 'label'=> 'Tolak Request', 'icon'=>'fa fa-times'],
                    'edit' => ['url' => Url::toRoute(['izin-by-baak-edit', 'id' => $model->izin_ruangan_id]), 'label'=> 'Edit Request', 'icon'=>'fa fa-pencil'],
                ],
                
            ]) ?>
    </div>

    <h1><?= $this->title ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'izin_ruangan_id',
            [
                'label' => 'Nama Pengaju',
                'value' => $model->dim->nama,
            ],
            [
                'attribute' => 'rencana_mulai',
                'value' => function($model){
                    if (is_null($model->rencana_mulai)) {
                        return '-';
                    }else{
                        return date('d M Y', strtotime($model->rencana_mulai));
                    }
                }
            ],
            [
                'attribute' => 'rencana_berakhir',
                'value' => function($model){
                    if (is_null($model->rencana_berakhir)) {
                        return '-';
                    }else{
                        return date('d M Y', strtotime($model->rencana_berakhir));
                    }
                }
            ],
            [
                'label' => 'Keterangan',
                'value' => $model->desc,
            ],
            'statusRequest.status_request',
        ],
    ]); ?>

    <?php }else { ?>

    <h1><?= $this->title ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?php
        echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'izin_tambahan_jam_kolaboratif_id',
            [
                'label' => 'Nama Pengaju',
                'value' => $model->dim->nama,
            ],
            [
                'attribute' => 'rencana_mulai',
                'value' => function($model){
                    if (is_null($model->rencana_mulai)) {
                        return '-';
                    }else{
                        return date('d M Y', strtotime($model->rencana_mulai));
                    }
                }
            ],
            [
                'attribute' => 'rencana_berakhir',
                'value' => function($model){
                    if (is_null($model->rencana_berakhir)) {
                        return '-';
                    }else{
                        return date('d M Y', strtotime($model->rencana_berakhir));
                    }
                }
            ],
            [
                'label' => 'Keterangan',
                'value' => $model->desc,
            ],
            'statusRequest.status_request',
            ['label' => $status, 'value' => function($model){
                    if (is_null($model->baak['nama'])) {
                        return '-';
                    }else{
                        return $model->baak['nama'];
                    }
                }
            ],
        ],
    ]); } ?>

</div>