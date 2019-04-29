<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\cist\models\search\PermohonanCutiTahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permohonan Cuti Tahunans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permohonan-cuti-tahunan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Permohonan Cuti Tahunan', ['add'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pmhnn_cuti_thn_id',
            'waktu_pelaksanaan',
            'alasan_cuti',
            'lama_cuti',
            'pengalihan_tugas',
            // 'status_oleh_hrd',
            // 'status_oleh_atasan',
            // 'status_oleh_wr2',
            // 'pegawai_id',
            // 'deleted',
            // 'deleted_at',
            // 'deleted_by',
            // 'created_by',
            // 'created_at',
            // 'updated_by',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
