<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\hrdx\models\search\RiwayatPendidikanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Riwayat Pendidikans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="riwayat-pendidikan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Riwayat Pendidikan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'riwayat_pendidikan_id',
            'jenjang_id',
            'universitas',
            'jurusan',
            'thn_mulai',
            // 'thn_selesai',
            // 'ipk',
            // 'gelar',
            // 'judul_ta:ntext',
            // 'pegawai_id',
            // 'website',
            // 'deleted',
            // 'deleted_at',
            // 'deleted_by',
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',
            // 'profile_id',
            // 'id_old',
            // 'jenjang',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
