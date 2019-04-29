<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\askm\models\search\IzinBermalamSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Izin Bermalam';
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="izin-bermalam-index">

    <?= $uiHelper->renderContentHeader($this->title);?>
    <?= $uiHelper->renderLine(); ?>

    <?php

        $toolbarItemMenu =  
            //"<a href='".Url::to(['izin-bermalam/izin-by-mahasiswa-index'])."' class='btn btn-info'><i class='fa fa-history'></i><span class='toolbar-label'>Daftar Request</span></a>
            "<a href='".Url::to(['izin-bermalam/izin-by-mahasiswa-add'])."' class='btn btn-success'><i class='fa fa-book'></i><span class='toolbar-label'>Request IB</span></a>
            "
            ;

    ?>

    <?=Yii::$app->uiHelper->renderToolbar([
    'pull-left' => true,
    'groupTemplate' => ['groupStatusExpired'],
    'groups' => [
        'groupStatusExpired' => [
            'template' => ['filterStatus'],
            'buttons' => [
                'filterStatus' => $toolbarItemMenu,
            ]
        ],
    ],
    ]) ?>

    <?=$uiHelper->beginContentRow() ?>
        
        <?=$uiHelper->beginContentBlock(['id' => 'grid-system2',
            'header' => 'Request sebelumnya',
            'width' => 12,
        ]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['style' => 'font-size:12px;'],
                // 'filterModel' => $searchModel,
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
                    
                    [
                        'attribute' => 'status_request_id',
                        'label' => 'Status Permohonan',
                        'value' => 'statusRequest.status_request',
                    ],
                    [
                        'attribute' => 'keasramaan_id',
                        'label' => 'Oleh',
                        'value' => function($model){
                            if (is_null($model->pegawai['nama'])) {
                                return '-';
                            }else{
                                return $model->pegawai['nama'];
                            }
                        }
                    ],
                    'desc:ntext',
                    'tujuan',
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
              
        <?= $uiHelper->endContentBlock()?>

    <?=$uiHelper->endContentRow() ?>

    <?=$uiHelper->beginContentRow() ?>
        
        <?=$uiHelper->beginContentBlock(['id' => 'grid-system2',
             'header' => 'Pedoman Izin Bermalam',
              'width' => 12,
              'type' => 'danger'
              ]); ?>
              <ul>
                    <li>
                        Mahasiswa diberikan Izin Bermalam di Luar Kampus (IBL) di hari Jumat atau
                        Sabtu atau di hari lain dimana keesokan harinya tidak ada kegiatan akademik
                        atau kegiatan lainnya yang tidak mengharuskan mahasiswa berada di kampus
                        IT Del.
                    </li>
                    <li>
                        Mahasiswa yang IBL wajib menjaga nama baik IT Del di luar kampus.
                    </li>
                    <li>
                        Mahasiswa mengisi pengajuan IBL di Aplikasi CIS
                        (https://cis.del.ac.id/askm/izin-bermalam) selambatnya H-2. Dan mencetak form IBL untuk
                        ditandatangani Bapak/Ibu Asrama dan ditunjukan di Pos Satpam saat keluar
                        kampus.
                    </li>
                    <li>
                        Pada saat kembali ke kampus, mahasiswa mengumpulkan kertas IBL yang
                        telah ditandatangani oleh orangtua di Pos Satpam untuk selanjutnya
                        dikumpulkan dan direkap oleh Pembina Asrama.
                    </li>
                    <li>
                        Apabila terdapat kegiatan Badan Eksekutif Mahasiswa (BEM) yang
                        mengharuskan seluruh mahasiswa mengikuti kegiatan tersebut, maka
                        mahasiswa tidak diperbolehkan IBL.
                    </li>
                    <li>
                        Mahasiswa yang tidak mengajukan IBL sesuai ketentuan pada butir 3 (tiga)
                        tidak diizinkan untuk IBL kecuali dalam kondisi mendesak (emergency)
                        seperti sakit atau ada keluarga meninggal
                    </li>
              </ul>
        <?= $uiHelper->endContentBlock()?>

    <?=$uiHelper->endContentRow() ?>

</div>