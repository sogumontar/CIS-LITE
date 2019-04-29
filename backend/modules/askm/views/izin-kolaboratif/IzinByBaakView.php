<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\modules\askm\models\Pegawai;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinTambahanJamKolaboratif */

$this->title = 'Data Request';
$this->params['breadcrumbs'][] = ['label' => 'Izin Tambahan Jam Kolaboratif', 'url' => ['izin-by-baak-index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
$baak = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
?>
<div class="izin-kolaboratif-view">

    <?php
        if ($model->status_request_id == 1) {
    ?>

    <div class="pull-right">
        Pengaturan
        <?= $uiHelper->renderButtonSet([
                'template' => ['approve', 'reject', 'edit'],
                'buttons' => [
                    'approve' => ['url' => Url::toRoute(['izin-by-baak-approve', 'id' => $model->izin_kolaboratif_id, 'id_baak' => $baak->pegawai_id]), 'label'=> 'Setujui Request', 'icon'=>'fa fa-check'], // id keasramaan diambil saat sudah login
                    'reject' => ['url' => Url::toRoute(['izin-by-baak-reject', 'id' => $model->izin_kolaboratif_id, 'id_baak' => $baak->pegawai_id]), 'label'=> 'Tolak Request', 'icon'=>'fa fa-times'],
                    'edit' => ['url' => Url::toRoute(['izin-by-baak-edit', 'id' => $model->izin_kolaboratif_id]), 'label'=> 'Edit Request', 'icon'=>'fa fa-pencil'],
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
