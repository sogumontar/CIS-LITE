<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\cist\models\search\PermohonanCutiNontahunanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permohonan Cuti Nontahunan Oleh WR2';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="permohonan-cuti-nontahunan-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['style' => 'font-size:12px;'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'pegawai_nama',
                'value' => 'pegawai.nama',
                'label' => 'Pemohon',
            ],
            [
                'attribute' => 'tgl_mulai',
                'value' => function($model){
                    return date('d M Y', strtotime($model->tgl_mulai));
                }
            ],
            [
                'attribute' => 'tgl_akhir',
                'value' => function($model){
                    return date('d M Y', strtotime($model->tgl_akhir));
                }
            ],
            [
                'attribute' => 'lama_cuti',
                'value' => 'lama_cuti',
                'label' => 'Lama Cuti (Hari)',
            ],
            // [
            //     'attribute' => 'alasan_cuti',
            //     'value' => 'alasan_cuti',
            //     'label' => 'Alasan Cuti',
            // ],
            [
                'attribute' => 'kategori_izin_id',
                'value'     => function($model) {
                    return $model->kategori->name;
                }
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
                    if ($model->statusCutiNontahunan->status_by_atasan == 5 || $model->statusCutiNontahunan->status_by_hrd == 5 || $model->statusCutiNontahunan->status_by_wr2 == 5) {
                        return 'Dibatalkan';
                    }  elseif ($model->statusCutiNontahunan->status_by_atasan == 1) {
                        return 'Menunggu Persetujuan Atasan';
                    } elseif ($model->statusCutiNontahunan->status_by_wr2 == 1) {
                        return 'Menunggu Persetujuan WR2';
                    } elseif ($model->statusCutiNontahunan->status_by_atasan == 4) {
                        return 'Ditolak oleh Atasan';
                    } elseif ($model->statusCutiNontahunan->status_by_wr2 == 4) {
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
                        if ($model->statusCutiNontahunan->status_by_hrd == 6 && $model->statusCutiNontahunan->status_by_atasan == 6 && $model->statusCutiNontahunan->status_by_wr2 == 1) {
                            return ToolsColumn::renderCustomButton($url, $model, 'Approve', 'fa fa-check');
                        }else if ($model->statusCutiNontahunan->status_by_hrd == 4 || $model->statusCutiNontahunan->status_by_atasan == 4) {
                            return "";
                        }else{
                            return "";
                        }
                    },
                    'tolak' => function ($url, $model){
                        if ($model->statusCutiNontahunan->status_by_hrd == 6 && $model->statusCutiNontahunan->status_by_atasan == 6 && $model->statusCutiNontahunan->status_by_wr2 == 1) {
                            return ToolsColumn::renderCustomButton($url, $model, 'Reject', 'fa fa-times');
                        }else if ($model->statusCutiNontahunan->status_by_hrd == 4 || $model->statusCutiNontahunan->status_by_atasan == 4) {
                            return "";
                        }else{
                            return "";
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index){
                    if ($action === 'view') {
                        return Url::toRoute(['view-by-wr2', 'id' => $key]);
                    } else if ($action === 'setuju') {
                        return Url::toRoute(['accept-by-wr2', 'id' => $key]);
                    } else if ($action === 'tolak') {
                        return Url::toRoute(['reject-by-wr2', 'id' => $key]);
                    }
                }

            ],
        ],
    ]); ?>

</div>
