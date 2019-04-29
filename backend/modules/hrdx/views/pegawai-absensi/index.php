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
$this->params['header'] = "Rekapitulasi Cuti dan Izin ".$pegawai->nama;

$uiHelper = \Yii::$app->uiHelper;
?>

<?= $uiHelper->beginContentRow() ?>

    <?= $uiHelper->beginContentBlock(['id' => 'grid-system1','width' => 12,]) ?>

        <div class="pull-right">
            <?php
            if (isset($user_role)) {
                $template=[
                    'cuti','izin'
                ];

                $buttons=[
                    'cuti' => ['url' => Url::toRoute(['histori-cuti','id'=>$pegawai->pegawai_id]), 'label' => 'Histori Cuti', 'icon' => 'fa fa-history'],
                    'izin' => ['url' => Url::toRoute(['histori-izin','id'=>$pegawai->pegawai_id]), 'label' => 'Histori Izin', 'icon' => 'fa fa-history'],
                ];

                foreach ($user_role as $key_user_role => $value_user_role) {
                    if ($value_user_role->role->name=='hrd') {
                        $template[]='antrian_hrd';
                        $buttons[]=$buttons['antrian_hrd']= ['url' => Url::toRoute(['antrian-absensi-as-hrd']), 'label' => 'Antrian Approval HRD', 'icon' => 'fa fa-group'];
                    }
                }

                $template[]='antrian_bawahan';
                $buttons[]=$buttons['antrian_bawahan']= ['url' => Url::toRoute(['antrian-absensi-as-atasan']), 'label' => 'Antrian Approval Atasan', 'icon' => 'fa fa-group'];

                echo $uiHelper->renderButtonSet([
                    'template' => $template,
                    'buttons' => $buttons
                ]); 
            }
            ?>
        </div>

            
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

    <?=$uiHelper->endContentBlock()?>

<?=$uiHelper->endContentRow() ?>
