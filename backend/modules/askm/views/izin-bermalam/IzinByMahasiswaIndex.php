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

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\IzinBermalamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List Izin Bermalam';
$this->params['breadcrumbs'][] = ['label' => 'Izin Bermalam', 'url' => ['index-mahasiswa']];
$this->params['breadcrumbs'][] = $this->title;

$status_url = urldecode('IzinBermalamSearch%5Bstatus_request_id%5D');

$uiHelper=\Yii::$app->uiHelper;
?>
<div class="izin-bermalam-index">
    
    <?= $uiHelper->renderContentSubHeader($this->title);?>
    <?= $uiHelper->renderLine(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Request Izin Bermalam', ['izin-by-mahasiswa-add'], ['class' => 'btn btn-success']) ?>
    </p>

    <h2><i class="fa fa-history"></i> Riwayat izin bermalam</h2>

    <?php
        $status1 = ($status_request_id == 0)?'active':'';
        $status2 = ($status_request_id == 1)?'active':'';
        $status3 = ($status_request_id == 2)?'active':'';
        $status4 = ($status_request_id == 3)?'active':'';

        $toolbarItemStatusRequest =  
            "<a href='".Url::to(['izin-bermalam/izin-by-mahasiswa-index'])."' class='btn btn-default ".$status1."'><i class='fa fa-list'></i><span class='toolbar-label'>All</span></a>
            <a href='".Url::to(['izin-bermalam/izin-by-mahasiswa-index', $status_url => 1])."' class='btn btn-info ".$status2."'><i class='fa fa-info'></i><span class='toolbar-label'>Requested</span></a>
            <a href='".Url::to(['izin-bermalam/izin-by-mahasiswa-index', $status_url => 2])."' class='btn btn-success ".$status3."'><i class='fa fa-check'></i><span class='toolbar-label'>Accepted</span></a>
            <a href='".Url::to(['izin-bermalam/izin-by-mahasiswa-index', $status_url => 3])."' class='btn btn-danger ".$status4."'><i class='fa fa-ban'></i><span class='toolbar-label'>Rejected</span></a>
            "
            ;

    ?>

    <?=Yii::$app->uiHelper->renderToolbar([
    'pull-right' => true,
    'groupTemplate' => ['groupStatusExpired'],
    'groups' => [
        'groupStatusExpired' => [
            'template' => ['filterStatus'],
            'buttons' => [
                'filterStatus' => $toolbarItemStatusRequest,
            ]
        ],
    ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['style' => 'font-size:12px;'],
        'filterModel' => $searchModel,
        'rowOptions' => function($model){
            if($model->status_request_id == 1){
                return ['class' => 'info'];
            } else if($model->status_request_id == 2){
                return ['class' => 'success'];
            } else if($model->status_request_id == 3){
                return ['class' => 'danger'];
            } else {
                return ['class' => 'warning'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'izin_bermalam_id',
            // 'dim_id',
            'rencana_berangkat',
            'rencana_kembali',
            // 'realisasi_berangkat',
            // 'realisasi_kembali',
            'desc:ntext',
            'tujuan',            
            // 'keasramaan_id',
            [
            'attribute' => 'keasramaan_id',
            'label' => 'Disetujui/Ditolak oleh',
            'value' => function($model){
                if (is_null($model->pegawai['nama'])) {
                    return '-';
                }else{
                    return $model->pegawai['nama'];
                }
            }
            ],
            // 'status_request_id',
            // [
            //     'attribute'=>'status_request_id',
            //     'label' => 'Status Request',
            //     'filter'=>ArrayHelper::map(StatusRequest::find()->asArray()->all(), 'status_request_id', 'status_request'),
            //     'value' => 'statusRequest.status_request',
            // ],
            // 'izin_laptop_id',

            // ['class' => 'yii\grid\ActionColumn','header' => 'Action',],
            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {edit} {cancel} {print}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    },
                    'cancel' => function ($url, $model){
                        if ($model->status_request_id == 2 || $model->status_request_id == 3 || $model->status_request_id == 4) {
                            return "";
                        }else{
                            return ToolsColumn::renderCustomButton($url, $model, 'Cancel', 'fa fa-times');
                        }
                    },
                    'edit' => function ($url, $model){
                        if ($model->status_request_id == 2 || $model->status_request_id == 3 || $model->status_request_id == 4) {
                            return "";
                        } else {
                            return ToolsColumn::renderCustomButton($url, $model, 'Edit', 'fa fa-pencil');
                        }
                    },
                    'print' => function ($url, $model){
                        if ($model->status_request_id == 2) {
                            return ToolsColumn::renderCustomButton($url, $model, 'Print', 'fa fa-print');
                        } else {
                            return "";
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['izin-by-mahasiswa-view', 'id' => $key]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['izin-by-mahasiswa-edit', 'id' => $key]);
                    }else if ($action === 'cancel') {
                        return Url::toRoute(['cancel-by-mahasiswa', 'id' => $key]);
                    }
                    else if ($action === 'print') {
                        return Url::toRoute(['print-ib', 'id' => $key]);
                    }
                    
                }
            ],
        ],
    ]); ?>
</div>
