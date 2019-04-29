<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rakx\models\search\ReviewProgram */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Review Programs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="review-program-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Review Program', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'review_program_id',
            'program_id',
            'struktur_jabatan_id',
            'review:ntext',
            'tanggal_review',
            // 'deleted',
            // 'deleted_at',
            // 'deleted_by',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
