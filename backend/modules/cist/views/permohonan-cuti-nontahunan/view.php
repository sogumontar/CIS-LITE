<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\PermohonanCutiNontahunan */

$this->title = $model->pmhnn_cuti_nthn_id;
$this->params['breadcrumbs'][] = ['label' => 'Permohonan Cuti Nontahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permohonan-cuti-nontahunan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->pmhnn_cuti_nthn_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->pmhnn_cuti_nthn_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pmhnn_cuti_nthn_id',
            'tgl_mulai',
            'tgl_akhir',
            'alasan_cuti',
            'lama_cuti',
            'kategori_id',
            'pengalihan_tugas',
            'status_oleh_hrd',
            'status_oleh_atasan',
            'status_oleh_wr2',
            'pegawai_id',
            'deleted',
            'deleted_at',
            'deleted_by',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
