<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rppx\models\search\PenugasanPengajaranSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Penugasan Pengajaran';
$this->params['breadcrumbs'][] = $this->title;
$this->params['layout'] = 'full';
?>
<div class="penugasan-pengajaran-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Penugasan Pengajaran', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'penugasan_pengajaran_id',
            'pengajaran_id',
            'pegawai_id',
            'role_pengajar_id',
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
