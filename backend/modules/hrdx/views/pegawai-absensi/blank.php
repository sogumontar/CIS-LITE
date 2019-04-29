<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\hrdx\models\search\PegawaiAbsensiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pegawai Absensi';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = "Rekapitulasi Cuti dan Izin";

$uiHelper = \Yii::$app->uiHelper;
?>

<?= $uiHelper->beginContentRow() ?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1','width' => 12,]) ?>
        <?= $uiHelper->beginTab([
            // 'header' => $model->pegawai['nama'],
            // 'icon' => 'fa fa-user',
            'tabs' => [
                ['id' => 'tab_1', 'label' => 'Quota Cuti dan Izin', 'isActive' => true],
                ['id' => 'tab_2', 'label' => 'History Cuti', 'isActive' => false],
                ['id' => 'tab_3', 'label' => 'History Izin', 'isActive' => false],
            ]
        ]) ?>

        <?= $uiHelper->beginTabContent(['id'=>'tab_1', 'isActive'=>true]) ?>

            Sisa Quota Cuti dan Izin.<br><br>
            
            <div class="krs-mhs">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Jenis Absen</th>
                            <th>Kuota</th>
                            <th>Sisa Kuota</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $count = 0;
                            foreach ($jenisAbsenAll as $absen) { 
                                if($absen->nama == 'Cuti Tahunan'){
                                    $absen->kuota = $totalKuotaCutiTahunan-($_kuotaCutiBersama->kuota);       
                                }
                        ?>
                        <tr>
                            <td><?=$absen->nama; ?></td>
                            <td><?=$absen->kuota. " ". $absen->satuan; ?></td>
                            <td><?= ($absen->nama == 'Cuti Tahunan'?$sisaKuota['tahunan']. " ". $absen->satuan:
                                    ($absen->nama == 'Cuti Menikah'?$sisaKuota['menikah']. " ". $absen->satuan:
                                    ($absen->nama == 'Cuti Kelahiran'?$sisaKuota['kelahiran']. " ". $absen->satuan:
                                    ($absen->nama == 'Cuti Melahirkan'?$sisaKuota['melahirkan']. " ". $absen->satuan:
                                    ($absen->nama == 'Cuti Kedukaan'?$sisaKuota['kedukaan']. " ". $absen->satuan:''))))); ?></td>
                            <td>
                                <p>
                                    <?= Html::a ('Request', Url::to(['request','pegawai_id'=>$pegawai->pegawai_id,'jenis_absen_id'=>$absen->jenis_absen_id]), ['class' => 'btn btn-default btn-xs dropdown-toggle',])?>
                                </p>
                            </td>
                        </tr>
                        <?php
                                $count++;
                            }
                        ?>
                    </tbody>
                </table>
            </div>

        <?= $uiHelper->endTabContent() ?>



        <?= $uiHelper->beginTabContent(['id'=>'tab_2', 'isActive'=>false]) ?>

            Hasil Rekapitulasi Cuti.<br><br>
            <?= GridView::widget([
                'dataProvider' => $dataProviderCuti,
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
                        'template' => '{view} {edit}',
                    
                        'urlCreator' => function ($action, $model, $key, $index) {
                                if($action === 'view'){
                                    return Url::to(['view', 'id'=> $key]);
                                }elseif ($action === 'edit') {
                                    return Url::to(['edit', 'id' => $key]);
                                }
                            }, 
                        'contentOptions' => ['class' => 'col-xs-1'],              
                    ],
                ],
            ]); ?>
            

        <?= $uiHelper->endTabContent() ?>


        <?= $uiHelper->beginTabContent(['id'=>'tab_3', 'isActive'=>false]) ?>

            Hasil Rekapitulasi Izin.<br><br>
            <?= GridView::widget([
                'dataProvider' => $dataProviderIzin,
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
                    [
                        'attribute' => 'status_approval_1',
                        'value' => function($data){
                            if($data->status_approval_1 == 0){
                                return "Not Approve";
                            }elseif($data->status_approval_2 == 0){
                                return "Approve";
                            }
                            else{
                                return "Rejected";
                            }
                        }
                    ],
                    [
                        'attribute' => 'approval_1',
                        'value' => 'approval1.nama',
                    ],
                    [
                        'attribute' => 'status_approval_2',
                        'value' => function($data){
                            if($data->status_approval_2 == 0){
                                return "Not Approve";
                            }elseif($data->status_approval_2 == 0){
                                return "Approve";
                            }
                            else{
                                return "Rejected";
                            }
                        }
                    ], 
                    [
                        'attribute' => 'approval_2',
                        'value' => 'approval2.nama',
                    ],  
                    
                ],
            ]); ?>
            

        <?= $uiHelper->endTabContent() ?>

    <?=$uiHelper->endContentBlock()?>

<?=$uiHelper->endContentRow() ?>
