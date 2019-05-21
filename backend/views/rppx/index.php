<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Penugasan Pengajarans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="penugasan-pengajaran-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Penugasan Pengajaran', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'penugasan_pengajaran_id',
            'pengajaran_id',
            'pegawai_id',
            'kaprodi_id',
            'kelas',
            // 'jumlah_kelas_riil',
            // 'kelas_tatap_muka',
            // 'role_pengajar_id',
            // 'load',
            // 'approved',
            // 'is_fulltime:datetime',
            // 'start_date',
            // 'end_date',
            // 'deleted',
            // 'deleted_by',
            // 'deleted_at',
            // 'created_at',
            // 'created_by',
            // 'updated_by',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
