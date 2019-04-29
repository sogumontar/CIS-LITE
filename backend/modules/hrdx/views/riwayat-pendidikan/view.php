<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\RiwayatPendidikan */

$this->title = $model->riwayat_pendidikan_id;
$this->params['breadcrumbs'][] = ['label' => 'Riwayat Pendidikans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="riwayat-pendidikan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->riwayat_pendidikan_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->riwayat_pendidikan_id], [
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
            'riwayat_pendidikan_id',
            'jenjang_id',
            'universitas',
            'jurusan',
            'thn_mulai',
            'thn_selesai',
            'ipk',
            'gelar',
            'judul_ta:ntext',
            'pegawai_id',
            'website',
            'deleted',
            'deleted_at',
            'deleted_by',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'profile_id',
            'id_old',
            'jenjang',
        ],
    ]) ?>

</div>
