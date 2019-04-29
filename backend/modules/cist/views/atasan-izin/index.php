<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\cist\models\search\AtasanIzinSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Atasan Izins';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atasan-izin-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Atasan Izin', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'atasan_izin_id',
            'pmhnn_izin_id',
            'pegawai_id',
            'nama',
            'deleted',
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
