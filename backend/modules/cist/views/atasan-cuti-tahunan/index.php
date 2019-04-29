<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\cist\models\search\AtasanCutiTahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Atasan Cuti Tahunans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atasan-cuti-tahunan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Atasan Cuti Tahunan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'atasan_cuti_id',
            'pmhnn_cuti_thn_id',
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
