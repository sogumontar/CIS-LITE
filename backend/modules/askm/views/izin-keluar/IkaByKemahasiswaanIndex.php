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
use backend\modules\askm\models\Pegawai;
use backend\modules\askm\models\Pedoman;
use dosamigos\datetimepicker\DateTimePicker;

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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= $uiHelper->renderLine(); ?>

    <div class="callout callout-info">
      <?php
        echo "<b>Keterangan</b><br/>";
        echo '1. Persetujuan berdasarkan flow Dosen Wali -> Keasramaan -> Baak<br/>';
        echo '2. Hanya bisa menyetujui/menolak yang sudah melalui flow sebelumnya<br/>';
        echo '3. Kemahasiswaan bisa menyetujui/menolak izin jika Dosen Wali atau Keasramaan berhalangan<br/>';
      ?>
    </div>

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

            // 'izin_keluar_id',
            // [
            //     'attribute'=>'dim_nama',
            //     'label' => 'Pemohon',
            //     'value' => 'dim.nama',
            // ],
            [
                'attribute' => 'dim_nama',
                'label'=>'Pemohon',
                'format' => 'raw',
                'value'=>function ($model) {
                    return "<a href='".Url::toRoute(['/dimx/dim/mahasiswa-view', 'dim_id' => $model->dim_id])."'>".$model->dim->nama."</a>";
                },
            ],
            [
                'attribute'=>'desc',
                'label' => 'Keperluan',
                'value' => 'desc',
            ],
            [
                'attribute'=>'rencana_berangkat',
                'label' => 'Rencana Berangkat',
                'format'=> 'raw',
                'headerOptions' => ['style' => 'color:#3c8dbc'],
                'value'=>function($model,$key,$index){
                    if($model->rencana_berangkat==NULL){
                        return '-';
                    }
                    else{
                        return date('d M Y H:i', strtotime($model->rencana_berangkat));
                    }
                },
                'filter'=>DateTimePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'rencana_berangkat',
                    'template'=>'{input}{reset}{button}',
                        'clientOptions' => [
                            'startView' => 2,
                            'minView' => 2,
                            'maxView' => 2,
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ],
                ])
            ],
            [
                'attribute'=>'rencana_kembali',
                'label' => 'Rencana Kembali',
                'format'=> 'raw',
                'headerOptions' => ['style' => 'color:#3c8dbc'],
                'value'=>function($model,$key,$index){
                    if($model->rencana_kembali==NULL){
                        return '-';
                    }
                    else{
                        return date('d M Y H:i', strtotime($model->rencana_kembali));
                    }
                },
                'filter'=>DateTimePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'rencana_kembali',
                    'template'=>'{input}{reset}{button}',
                        'clientOptions' => [
                            'startView' => 2,
                            'minView' => 2,
                            'maxView' => 2,
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ],
                ])
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
                'template' => '{view} {approve1} {approve2} {reject1} {reject2}',
                'header' => 'Aksi',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    },
                    'approve1' => function ($url, $model){
                        if ($model->status_request_dosen_wali == 2 || $model->status_request_dosen_wali == 3 || $model->status_request_dosen_wali == 4) {
                            return "";
                        }else{
                            return ToolsColumn::renderCustomButton($url, $model, 'Approve as Dosen', 'fa fa-check');
                        }
                    },
                    'approve2' => function ($url, $model){
                        if ($model->status_request_keasramaan == 2 || $model->status_request_keasramaan == 3 || $model->status_request_keasramaan == 4) {
                            return "";
                        }else if($model->status_request_dosen_wali == 2){
                            return ToolsColumn::renderCustomButton($url, $model, 'Approve as Keasramaan', 'fa fa-check');
                        }
                    },
                    'reject1' => function ($url, $model){
                        if ($model->status_request_dosen_wali == 2 || $model->status_request_dosen_wali == 3 || $model->status_request_dosen_wali == 4) {
                            return "";
                        }else{
                            return ToolsColumn::renderCustomButton($url, $model, 'Reject as Dosen', 'fa fa-times');
                        }
                    },
                    'reject2' => function ($url, $model){
                        if ($model->status_request_keasramaan == 2 || $model->status_request_keasramaan == 3 || $model->status_request_keasramaan == 4) {
                            return "";
                        }else if($model->status_request_dosen_wali == 2){
                            return ToolsColumn::renderCustomButton($url, $model, 'Reject as Keasramaan', 'fa fa-times');
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    $kemahasiswaan = Pegawai::find()->where('deleted != 1')->andWhere(['user_id' => Yii::$app->user->identity->user_id])->one();
                    if ($action === 'view') {
                        return Url::toRoute(['ika-by-kemahasiswaan-view', 'id' => $key]);
                    }else if ($action === 'approve1') {
                        return Url::toRoute(['approve-by-kemahasiswaan-dosen', 'id' => $key, 'id_kemahasiswaan' => $kemahasiswaan->pegawai_id]);
                    }else if ($action === 'approve2') {
                        return Url::toRoute(['approve-by-maha-asra', 'id' => $key, 'id_kemahasiswaan' => $kemahasiswaan->pegawai_id]);
                    }else if ($action === 'reject1') {
                        return Url::toRoute(['reject-by-kemahasiswaan-dosen', 'id' => $key, 'id_kemahasiswaan' => $kemahasiswaan->pegawai_id]);
                    }else if ($action === 'reject2') {
                        return Url::toRoute(['reject-by-maha-asra', 'id' => $key, 'id_kemahasiswaan' => $kemahasiswaan->pegawai_id]);
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
