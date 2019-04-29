<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\cist\models\search\PermohonanCutiNontahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permohonan Cuti Nontahunans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="permohonan-cuti-nontahunan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Permohonan Cuti Nontahunan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'permohonan_cuti_nontahunan_id',
            'tgl_mulai',
            'tgl_akhir',
            'alasan_cuti',
            'lama_cuti',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
