<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinTambahanJamKolaboratif */

$this->title = 'Data Request';
$this->params['breadcrumbs'][] = ['label' => 'Izin Tambahan Jam Kolaboratif', 'url' => ['izin-by-mahasiswa-index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="izin-kolaboratif-view">

    <?php
        if ($model->status_request_id == 1) {
    ?>

    <div class="pull-right">
        Pengaturan
        <?= $uiHelper->renderButtonSet([
                'template' => ['edit', 'cancel'],
                'buttons' => [
                    'edit' => ['url' => Url::toRoute(['izin-by-mahasiswa-edit', 'id' => $model->izin_kolaboratif_id]), 'label'=> 'Edit Request', 'icon'=>'fa fa-pencil'], // id keasramaan diambil saat sudah login
                    'cancel' => ['url' => Url::toRoute(['cancel-by-mahasiswa', 'id' => $model->izin_kolaboratif_id]), 'label'=> 'Cancel Request', 'icon'=>'fa fa-times'],
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
            // 'izin_kolaboratif_id',
            'dim.nama',
            'desc:ntext',
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
            'batas_waktu',
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
    ]) ?>

</div>
