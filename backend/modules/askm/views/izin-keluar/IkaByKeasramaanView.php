<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\modules\askm\models\Pegawai;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinKeluar */

$this->title = "Detail Izin Keluar";
$this->params['breadcrumbs'][] = ['label' => 'Izin Keluar', 'url' => ['ika-by-keasramaan-index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
$keasramaan = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
?>
<div class="izin-keluar-view">

    <?php
        if($model->status_request_dosen_wali == 2 && $model->status_request_keasramaan == 1){
    ?>

    <div class="pull-right">
        Pengaturan
        <?= $uiHelper->renderButtonSet([
                'template' => ['approve', 'reject'],
                'buttons' => [
                    'approve' => ['url' => Url::toRoute(['approve-by-keasramaan', 'id' => $model->izin_keluar_id, 'id_keasramaan' => $keasramaan->pegawai_id]), 'label'=> 'Setujui Request', 'icon'=>'fa fa-check'], // id keasramaan diambil saat sudah login
                    'reject' => ['url' => Url::toRoute(['reject-by-keasramaan', 'id' => $model->izin_keluar_id, 'id_keasramaan' => $keasramaan->pegawai_id]), 'label'=> 'Tolak Request', 'icon'=>'fa fa-times'],
                ],

            ]) ?>
    </div>

    <?php } ?>

    <h1><?= $this->title ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['label' => 'Pemohon', 'value' => $model->dim['nama']],
            ['label' => 'NIM Mahasiswa', 'value' => $model->dim['nim']],
            'desc:ntext',
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
            [
                'attribute' => 'status_request_dosen_wali',
                'value' => function($model){
                    if (is_null($model->statusRequestDosen->status_request)) {
                        return '-';
                    }else{
                        return $model->statusRequestDosen->status_request;
                    }
                }
            ],
            [
                'label' => 'Disetujui/Ditolak oleh',
                'value' => function($model){
                    if (is_null($model->dosen['nama'])) {
                        return '-';
                    }else{
                        return $model->dosen['nama'];
                    }
                }
            ],
            [
                'attribute' => 'status_request_keasramaan',
                'value' => function($model){
                    if (is_null($model->statusRequestKeasramaan->status_request)) {
                        return '-';
                    }else{
                        return $model->statusRequestKeasramaan->status_request;
                    }
                }
            ],
            [
                'label' => 'Disetujui/Ditolak oleh',
                'value' => function($model){
                    if (is_null($model->keasramaan['nama'])) {
                        return '-';
                    }else{
                        return $model->keasramaan['nama'];
                    }
                }
            ],
            [
                'attribute' => 'status_request_baak',
                'value' => function($model){
                    if (is_null($model->statusRequestBaak->status_request)) {
                        return '-';
                    }else{
                        return $model->statusRequestBaak->status_request;
                    }
                }
            ],
            [
                'label' => 'Disetujui/Ditolak oleh',
                'value' => function($model){
                    if (is_null($model->baak['nama'])) {
                        return '-';
                    }else{
                        return $model->baak['nama'];
                    }
                }
            ],
            // ['attribute' => 'status_request_dosen_wali', 'value' => $model->status_request_dosen_wali['status_request']],
        ],
    ]);
    ?>

</div>
