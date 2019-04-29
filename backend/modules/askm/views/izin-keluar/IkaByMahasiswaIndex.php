<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\SwitchColumn;
use common\components\ToolsColumn;
use common\helpers\LinkHelper;
use backend\modules\askm\models\StatusRequest;
use backend\modules\askm\models\Dim;
use backend\modules\askm\models\Pedoman;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\IzinKeluarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Izin Keluar';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
$pedoman = Pedoman::find()->where('deleted!=1')->andWhere(['jenis_izin' => 2])->one();
?>
<div class="izin-keluar-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Request Izin Keluar', ['ika-by-mahasiswa-add'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model){
            if($model->status_request_keasramaan == 2 && $model->status_request_dosen_wali == 2 && $model->status_request_baak == 2){
                return ['class' => 'success'];
            } else if($model->status_request_keasramaan == 3 || $model->status_request_dosen_wali == 3 || $model->status_request_baak == 3){
                return ['class' => 'danger'];
            } else if($model->status_request_keasramaan == 4 || $model->status_request_dosen_wali == 4 || $model->status_request_baak == 4){
                return ['class' => 'warning'];
            } else {
                return ['class' => 'info'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute'=>'desc',
                'label' => 'Keperluan',
                'value' => 'desc',
            ],

            [
                'label' => 'Status Request',
                'filter' => '',
                'value' => function($model){
                    if ($model->status_request_dosen_wali == 4 || $model->status_request_keasramaan == 4 || $model->status_request_baak == 4) {
                        return 'Dibatalkan';
                    } else if ($model->status_request_dosen_wali == 1) {
                        return 'Menunggu Persetujuan Dosen Wali';
                    } elseif ($model->status_request_keasramaan == 1) {
                        return 'Menunggu Persetujuan Keasramaan';
                    } elseif ($model->status_request_baak == 1) {
                        return 'Menunggu Persetujuan BAAK';
                    } elseif ($model->status_request_dosen_wali == 3) {
                        return 'Ditolak oleh Dosen Wali';
                    } elseif ($model->status_request_keasramaan == 3) {
                        return 'Ditolak oleh Keasramaan';
                    } elseif ($model->status_request_baak == 3) {
                        return 'Ditolak oleh BAAK';
                    } else {
                        return 'Selesai';
                    }
                },
            ],

            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {edit} {cancel}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    },
                    'cancel' => function ($url, $model){
                        if ($model->status_request_dosen_wali == 1 || $model->status_request_keasramaan == 1 || $model->status_request_baak == 1) {
                            return ToolsColumn::renderCustomButton($url, $model, 'Cancel', 'fa fa-times');
                        }else if ($model->status_request_dosen_wali != 1 || $model->status_request_keasramaan != 1 || $model->status_request_baak != 1){
                            return "";
                        }
                    },
                    'edit' => function ($url, $model){
                        if ($model->status_request_dosen_wali != 1 || $model->status_request_keasramaan != 1 || $model->status_request_baak != 1) {
                            return "";
                        }else{
                            return ToolsColumn::renderCustomButton($url, $model, 'Edit', 'fa fa-pencil');
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['ika-by-mahasiswa-view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['ika-by-mahasiswa-edit', 'id' => $key]);
                    }else if ($action === 'cancel') {
                        return Url::toRoute(['ika-by-mahasiswa-cancel', 'id' => $key]);
                    }
                }
            ],
        ],
    ]); ?>

    <?=$uiHelper->beginContentRow() ?>

        <?=$uiHelper->beginContentBlock(['id' => 'grid-system2',
            'header' => $pedoman->judul,
            'type' => 'danger',
            'width' => 12,
        ]); ?>

        <?= $pedoman->isi ?>

        <?= $uiHelper->endContentBlock()?>

    <?=$uiHelper->endContentRow() ?>

</div>
