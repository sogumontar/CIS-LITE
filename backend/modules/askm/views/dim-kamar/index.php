<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\DimKamar */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dim Kamars';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dim-kamar-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Dim Kamar', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_dim_kamar',
            'status_dim_kamar',
            'dim_id',
            'kamar_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
