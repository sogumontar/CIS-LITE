<?php

use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use backend\modules\hrdx\controllers\PegawaiAbsensiController;


/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\PegawaiAbsensi */

// Untuk label breadcrum
$temp_jenis_absensi=substr($absensi->jenisAbsen->nama, 0,4);
$temp_label=$temp_jenis_absensi=="Cuti"?"Histori Cuti":"Histori Izin";
$temp_url=$temp_jenis_absensi=="Cuti"?['histori-cuti','id'=>$absensi->pegawai_id]:['histori-cuti','id'=>$absensi->pegawai_id];

$temp_judul=$temp_jenis_absensi=="Cuti"?"Cuti":"Izin";

$this->title = 'Detail '.$temp_judul;
$this->params['breadcrumbs'][] = ['label' => 'Pegawai Absensi', 'url' => ['browse']];
$this->params['breadcrumbs'][] = ['label' => $temp_label, 'url' => $temp_url];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title.' '.$absensi->pegawai->nama;
$uiHelper=\Yii::$app->uiHelper;
?>

<?= $uiHelper->beginContentRow() ?>
    <?= $uiHelper->beginContentBlock(['id' => 'grid-detail','width' => 12,]) ?>

    




    <?php
    	// Jika yang ingin mengaprove adalah HRD
        if ($is_hrd==1) {
        	if ($absensi->status_approval_2==0) {	
    ?>

    <div class="pull-left">
        <a href="<?=Url::toRoute(['penerimaan-absensi','id'=>$absensi->pegawai_absensi_id,'approval'=>2,'status'=>1])?>" class="btn btn-success">Terima</a>
        <br>
        <br>
    </div>

    <?php
    		}
        }
    ?>

    

    <?=$uiHelper->endContentBlock()?>
<?=$uiHelper->endContentRow() ?>

<?= $uiHelper->beginContentRow() ?>
    <?= $uiHelper->beginContentBlock(['id' => 'grid-detail','width' => 12,]) ?>
    <table class="table table-striped table-bordered detail-view">
    	<tbody>
	    	<tr>
	    		<th>Nama Absensi</th>
	    		<td><?=$absensi->jenisAbsen->nama?></td>
	    	</tr>
			<tr>
				<th>Alasan</th>
				<td><?=HtmlPurifier::process($absensi->alasan)?></td>
			</tr>
			<tr>
				<th>Jumlah Hari</th>
				<td><?=$absensi->jumlah_hari?></td>
			</tr>
			<tr>
				<th>Tanggal Awal</th>
				<td><?=$absensi->dari_tanggal?></td>
			</tr>
			<tr>
				<th>Tanggal Akhir</th>
				<td><?=$absensi->sampai_tanggal?></td>
			</tr>
			<tr>
				<th>Pengalihan Tugas</th>
				<?php
					$temp_array=preg_split("/;/", $absensi->pengalihan_tugas);
					foreach ($temp_array as $key_temp_array => $value_temp_array) {
						$temp_array[$key_temp_array]=PegawaiAbsensiController::getPegawaiById($value_temp_array);
					}
					$temp_string=implode(", ", $temp_array);
				?>
				<td><?=$temp_string?></td>
			</tr>
		</tbody>
	</table>
    <?=$uiHelper->endContentBlock()?>
<?=$uiHelper->endContentRow() ?>
