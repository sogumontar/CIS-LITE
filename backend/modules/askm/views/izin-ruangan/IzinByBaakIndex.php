<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\SwitchColumn;
use common\components\ToolsColumn;
use common\helpers\LinkHelper;
use backend\modules\askm\models\Lokasi;
use backend\modules\askm\models\Pegawai;
use backend\modules\askm\models\Pedoman;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\IzinRuanganSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Izin';
$this->params['breadcrumbs'][] = ['label' => 'Izin Penggunaan Ruangan', 'url' => ['index-baak']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Izin Penggunaan Ruangan';
$pedoman = Pedoman::find()->where('deleted!=1')->andWhere(['jenis_izin' => 3])->one();

$status_url = urldecode('IzinRuanganSearch%5Bstatus_request_id%5D');
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="izin-ruangan-index">
    
    <?= $uiHelper->renderContentSubHeader(' List Request '.$this->title, ['icon' => 'fa fa-list']);?>
    <?= $uiHelper->renderLine(); ?>

    <?php
        $status1 = ($status_request_id == 0)?'active':'';
        $status2 = ($status_request_id == 1)?'active':'';
        $status3 = ($status_request_id == 2)?'active':'';
        $status4 = ($status_request_id == 3)?'active':'';

        $toolbarItemStatusRequest =  
            "<a href='".Url::to(['izin-ruangan/izin-by-baak-index'])."' class='btn btn-default ".$status1."'><i class='fa fa-list'></i><span class='toolbar-label'>All</span></a>
            <a href='".Url::to(['izin-ruangan/izin-by-baak-index', $status_url => 1])."' class='btn btn-info ".$status2."'><i class='fa fa-info'></i><span class='toolbar-label'>Requested</span></a>
            <a href='".Url::to(['izin-ruangan/izin-by-baak-index', $status_url => 2])."' class='btn btn-success ".$status3."'><i class='fa fa-check'></i><span class='toolbar-label'>Accepted</span></a>
            <a href='".Url::to(['izin-ruangan/izin-by-baak-index', $status_url => 3])."' class='btn btn-danger ".$status4."'><i class='fa fa-ban'></i><span class='toolbar-label'>Rejected</span></a>
            <a href='".Url::to(['izin-by-baak-index', $status_url => 4])."' class='btn btn-warning ".$status4."'><i class='fa fa-times'></i><span class='toolbar-label'>Canceled</span></a>
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
        'filterModel' => $searchModel,
        'rowOptions' => function($model){
            if($model->status_request_id == 2){
                return ['class' => 'success'];
            } else if($model->status_request_id == 3){
                return ['class' => 'danger'];
            } else if($model->status_request_id == 4) {
                return ['class' => 'warning'];
            } else {
                return ['class' => 'info'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'attribute' => 'dim_nama',
                'label'=>'Nama Mahasiswa',
                'format' => 'raw',
                'value'=>function ($model) {
                    return "<a href='".Url::toRoute(['/dimx/dim/mahasiswa-view', 'dim_id' => $model->dim_id])."'>".$model->dim->nama."</a>";
                },
            ],
            'rencana_mulai',
            'rencana_berakhir',
            'desc:ntext',
            [
                'attribute'=>'lokasi_id',
                'label' => 'Ruangan',
                'filter'=>ArrayHelper::map(Lokasi::find()->andWhere('deleted!=1')->asArray()->all(), 'lokasi_id', 'name'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'value' => 'lokasi.name',
            ],

            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {approve} {reject}',
                 'template' => '{view} {edit} {approve} {reject}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    },
                    'approve' => function ($url, $model){
                        if ($model->status_request_id == 2 || $model->status_request_id == 3 || $model->status_request_id == 4) {
                            return "";
                        }else{
                            return ToolsColumn::renderCustomButton($url, $model, 'Approve', 'fa fa-check');
                        }
                    },
                    'reject' => function ($url, $model){
                        if ($model->status_request_id == 2 || $model->status_request_id == 3 || $model->status_request_id == 4) {
                            return "";
                        }else{
                            return ToolsColumn::renderCustomButton($url, $model, 'Reject', 'fa fa-times');
                        }
                    },
                    'edit' => function ($url, $model){
                        if ($model->status_request_id == 2 || $model->status_request_id == 3 || $model->status_request_id == 4) {
                            return "";
                        }else{
                            return ToolsColumn::renderCustomButton($url, $model, 'Edit', 'fa fa-pencil');
                        }
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    $baak = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
                    if ($action === 'view') {
                        return Url::toRoute(['izin-by-baak-view', 'id' => $model->izin_ruangan_id]);
                    }else if ($action === 'approve') {
                        return Url::toRoute(['approve-by-baak', 'id' => $model->izin_ruangan_id, 'id_baak' => $baak->pegawai_id]);
                    }else if ($action === 'reject') {
                        return Url::toRoute(['reject-by-baak', 'id' => $model->izin_ruangan_id, 'id_baak' => $baak->pegawai_id]);
                    }else if ($action === 'edit') {
                        return Url::toRoute(['izin-by-baak-edit', 'id' => $model->izin_ruangan_id]);
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

