<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\ToolsColumn;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\PedomanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pedoman Izin Mahasiswa';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pedoman-index">

    <?= $uiHelper->renderContentHeader($this->title);?>
    <?= $uiHelper->renderLine(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!-- <p>
        <?= Html::a('Buat Pedoman', ['add'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'pedoman_id',
            'judul',
            // 'isi:ntext',
            // 'jenis_izin',
            // 'deleted',
            // 'deleted_at',
            // 'deleted_by',
            // 'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',

            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {edit}',
                 'template' => '{view} {edit}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View', 'fa fa-eye');
                    },
                    'edit' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'Edit', 'fa fa-pencil');
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['edit', 'id' => $key]);
                    }
                    
                }
            ],
        ],
    ]); ?>

</div>
