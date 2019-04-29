<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\DimPelanggaranSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'askm_dim_pelanggaran';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="dim-pelanggaran-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pelanggaran_id',
            'pembinaan_id',
            'penilaian_id',
            'poin_id',
            'tanggal',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
