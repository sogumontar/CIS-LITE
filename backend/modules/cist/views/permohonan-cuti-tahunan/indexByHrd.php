<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\SwitchColumn;
use common\components\ToolsColumn;
use common\helpers\LinkHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\cist\models\search\PermohonanCutiTahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permohonan Cuti Tahunan Oleh HRD';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="permohonan-cuti-tahunan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => ['style' => 'font-size:12px;'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'pegawai_nama',
                'value' => 'pegawai.nama',
                'label' => 'Pemohon',
            ],
            // [
            //     'attribute' => 'lama_cuti',
            //     'value' => 'lama_cuti',
            //     'label' => 'Lama Cuti (Hari)',
            // ],
            [
                'attribute' => 'waktu_pelaksanaan',
                'value' => function($model){
                    $date = explode(',', $model->waktu_pelaksanaan);
                    $date2 = '';
                    $first = true;
                    foreach ($date as $d) {
                        $d = trim($d);
                         if(!$first){
                             $date2.= '<br />';
                        }
                        $date2.= '- '.date('d M Y', strtotime($d));
                        if ($first) {
                             $first = false;
                        }
                    }
                    return $date2;
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'alasan_cuti',
                'value' => 'alasan_cuti',
                'label' => 'Alasan Cuti',
            ],
            [
                'attribute' => 'pengalihan_tugas',
                'value' => 'pengalihan_tugas',
                'label' => 'Pengalihan Tugas',
            ],

            [
                'label' => 'Status Request',
                'filter' => '',
                'value' => function($model){
                    if ($model->statusIzin->status_by_atasan == 5 || $model->statusIzin->status_by_wr2 == 5) {
                        return 'Dibatalkan';
                    } elseif ($model->statusIzin->status_by_atasan == 1) {
                        return 'Menunggu Persetujuan Atasan';
                    } elseif ($model->statusIzin->status_by_wr2 == 1) {
                        return 'Menunggu Persetujuan WR2';
                    } elseif ($model->statusIzin->status_by_atasan == 4) {
                        return 'Ditolak oleh Atasan';
                    } elseif ($model->statusIzin->status_by_wr2 == 4) {
                        return 'Ditolak oleh WR2';
                    } else {
                        return 'Disetujui';
                    }
                },
            ],

            ['class' => 'common\components\ToolsColumn',
                'template' => '{view} {setuju} {tolak}',
                'header' => 'Action',
                'buttons' => [
                    'view' => function ($url, $model){
                        return ToolsColumn::renderCustomButton($url, $model, 'View Detail', 'fa fa-eye');
                    },
                    'setuju' => function ($url, $model){
                        if ($model->statusCutiTahunan->status_by_atasan == 6 && $model->statusCutiTahunan->status_by_wr2 == 1) {
                            return ToolsColumn::renderCustomButton($url, $model, 'Approve sebagai WR2', 'fa fa-check');
                        }else if ($model->statusCutiTahunan->status_by_wr2 == 1) {
                            return ToolsColumn::renderCustomButton($url, $model, 'Approve sebagai Atasan', 'fa fa-check');
                        }else if ($model->statusCutiTahunan->status_by_atasan == 4) {
                            return "";
                        }else{
                            return "";
                        }
                    },
                    'tolak' => function ($url, $model){
                        if ($model->statusCutiTahunan->status_by_atasan == 6 && $model->statusCutiTahunan->status_by_wr2 == 1) {
                            return ToolsColumn::renderCustomButton($url, $model, 'Reject sebagai WR2', 'fa fa-times');
                        }else if ($model->statusCutiTahunan->status_by_wr2 == 1) {
                            return ToolsColumn::renderCustomButton($url, $model, 'Reject sebagai Atasan', 'fa fa-times');
                        }else if ($model->statusCutiTahunan->status_by_atasan == 4) {
                            return "";
                        }else{
                            return "";
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['view-by-hrd', 'id' => $key]);
                    } else if ($action === 'setuju') {
                        if ($model->statusCutiTahunan->status_by_atasan == 6 && $model->statusCutiTahunan->status_by_wr2 == 1) {
                            return Url::toRoute(['accept-by-wr2-hrd', 'id' => $key]);
                        }else if ($model->statusCutiTahunan->status_by_wr2 == 1) {
                            return Url::toRoute(['accept-by-atasan-hrd', 'id' => $model->permohonan_cuti_tahunan_id]);
                        }
                    } else if ($action === 'tolak') {
                        if ($model->statusCutiTahunan->status_by_atasan == 6 && $model->statusCutiTahunan->status_by_wr2 == 1) {
                            return Url::toRoute(['reject-by-wr2-hrd', 'id' => $key]);
                        }else if ($model->statusCutiTahunan->status_by_wr2 == 1) {
                            return Url::toRoute(['reject-by-atasan-hrd', 'id' => $model->permohonan_cuti_tahunan_id]);
                        }
                    }
                }

            ],

        ],
    ]); ?>

</div>
