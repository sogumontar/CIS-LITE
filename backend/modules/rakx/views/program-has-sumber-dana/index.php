<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\rakx\models\search\ProgramHasSumberDanaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Program Has Sumber Danas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="program-has-sumber-dana-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Program Has Sumber Dana', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'program_has_sumber_dana_id',
            'program_id',
            'sumber_dana_id',
            'jumlah',
            [
                'attribute' => 'desc',
                'format' => 'html',
            ],
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
