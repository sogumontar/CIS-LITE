<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\hrdx\models\search\PegawaiAbsensiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Antrian Absensi';
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Absensi', 'url' => ['browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;

$uiHelper = \Yii::$app->uiHelper;
?>

<?= $uiHelper->beginContentRow() ?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1','width' => 12,]) ?>

        <?= GridView::widget([
                'dataProvider' => $dataProviderAbsensi,
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

                    // 'jumlah_hari',
                    'dari_tanggal',               
                    'sampai_tanggal',
                    'jumlah_hari',
                    [
                        'attribute' => 'status_approval_1',
                        'value' => function($data){
                            if($data->status_approval_1 == 0){
                                return "Not Approve";
                            }elseif($data->status_approval_2 == 1){
                                return "Approve";
                            }
                            else{
                                return "Rejected";
                            }
                        }
                    ],
                    // [
                    //     'attribute' => 'approval_1',
                    //     'value' => 'approval1.nama',
                    // ],
                    [
                        'attribute' => 'status_approval_2',
                        'value' => function($data){
                            if($data->status_approval_2 == 0){
                                return "Not Approved";
                            }elseif($data->status_approval_2 == 1){
                                return "Approved";
                            }
                            else{
                                return "Rejected";
                            }
                        }
                    ],
                    // [
                    //     'attribute' => 'approval_2',
                    //     'value' => 'approval2.nama',
                    // ],
                    [
                        'class' => 'common\components\ToolsColumn', 
                        'template' => '{view}',
                    
                        'urlCreator' => function ($action, $model, $key, $index) {
                                if($action === 'view'){
                                    return Url::to(['view', 'id'=> $key]);
                                }
                            }, 
                        'contentOptions' => ['class' => 'col-xs-1'],              
                    ],
                ],
            ]); ?>

    <?=$uiHelper->endContentBlock()?>

<?=$uiHelper->endContentRow() ?>
