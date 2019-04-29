<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\PegawaiAbsensi */
$uiHelper = \Yii::$app->uiHelper;

$this->title = 'Approve Izin/Cuti';
$this->params['breadcrumbs'][] = ['label' => 'Cuti/Izin', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

?>

<?= $uiHelper->beginContentRow() ?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1','width' => 12,]) ?>

        <?= $uiHelper->beginTab([
            'tabs' => [
                ['id' => 'tab_1', 'label' => 'Belum Approve', 'isActive' => true],
                ['id' => 'tab_2', 'label' => 'Approved', 'isActive' => false],
                ['id' => 'tab_3', 'label' => 'Rejected', 'isActive' => false],
            ]
        ]) ?>


        <?= $uiHelper->beginTabContent(['id'=>'tab_1', 'isActive'=>true]) ?>

            Berikut ini merupakan daftar Izin/Cuti pegawai yang harus anda review.<br><br>
            <?= GridView::widget([
                'dataProvider' => $dataProviderPegawaiAbsensiBelumApp,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-condensed table-bordered'],
                'layout' => "{items}\n{pager}{summary}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'], 
                    [
                        'attribute' => 'pegawai_id',
                        'value' => 'pegawai.nama',
                    ],
                    [
                        'attribute' => 'jenis_absen_id',
                        'value' => 'jenisAbsen.nama',
                    ],               

                    'dari_tanggal',               
                    'sampai_tanggal',                  
                    [
                      'class' => 'common\components\ToolsColumn',
                      'template' => '{view}',
                      'buttons' => [
                                    'view' => function ($url, $model){
                                        return ToolsColumn::renderCustomButton($url, $model, 'Lihat detail request', 'fa fa-eye');
                                    },
                                ],
                      'urlCreator' => function ($action, $model, $key, $index){
                        if ($action === 'view') {
                            return Url::to(['view','id' => $model->pegawai_absensi_id]);
                        }
                      }
                    
                    ],
                ],
            ]); ?>
            

        <?= $uiHelper->endTabContent() ?>

        <?= $uiHelper->beginTabContent(['id'=>'tab_2', 'isActive'=>false]) ?>

            Berikut ini merupakan history daftar Izin/Cuti pegawai yang sudah anda approve.<br><br>
            <?= GridView::widget([
                'dataProvider' => $dataProviderPegawaiAbsensiSudahApp,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-condensed table-bordered'],
                'layout' => "{items}\n{pager}{summary}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'], 
                    [
                        'attribute' => 'pegawai_id',
                        'value' => 'pegawai.nama',
                    ],
                    [
                        'attribute' => 'jenis_absen_id',
                        'value' => 'jenisAbsen.nama',
                    ],               

                    'dari_tanggal',               
                    'sampai_tanggal',                  
                    ['class' => 'common\components\ToolsColumn',
                     'header' => ' ',
                     'template' => '{lihat_detail_request}',
                     'buttons' => [
                            'lihat_detail_request' => function ($url, $model){
                                return ToolsColumn::renderCustomButton($url, $model, 'Lihat detail request', 'fa fa-shield');
                            },
                            
                        ],
                     'urlCreator' => function ($action, $model, $key, $index){
                        if($action === 'lihat_detail_request'){
                             return Url::to(['/hrdx/pegawai-absensi/view','id' => $model->pegawai_absensi_id]);
                        }
                      }

                    ],
                ],
            ]); ?>
            

        <?= $uiHelper->endTabContent() ?>

        <?= $uiHelper->beginTabContent(['id'=>'tab_3', 'isActive'=>false]) ?>

            Berikut ini merupakan history daftar Izin/Cuti pegawai yang sudah anda reject.<br><br>
            <?= GridView::widget([
                'dataProvider' => $dataProviderPegawaiAbsensiReject,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-condensed table-bordered'],
                'layout' => "{items}\n{pager}{summary}",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'], 
                    [
                        'attribute' => 'pegawai_id',
                        'value' => 'pegawai.nama',
                    ],
                    [
                        'attribute' => 'jenis_absen_id',
                        'value' => 'jenisAbsen.nama',
                    ],               

                    'dari_tanggal',               
                    'sampai_tanggal',                  
                    ['class' => 'common\components\ToolsColumn',
                     'header' => ' ',
                     'template' => '{lihat_detail_request}',
                     'buttons' => [
                            'lihat_detail_request' => function ($url, $model){
                                return ToolsColumn::renderCustomButton($url, $model, 'Lihat detail request', 'fa fa-shield');
                            },
                            
                        ],
                     'urlCreator' => function ($action, $model, $key, $index){
                        if($action === 'lihat_detail_request'){
                             return Url::to(['/hrdx/pegawai-absensi/view','id' => $model->pegawai_absensi_id]);
                        }
                      }

                    ],
                ],
            ]); ?>
            

        <?= $uiHelper->endTabContent() ?>

    <?=$uiHelper->endContentBlock()?>

<?=$uiHelper->endContentRow() ?>
