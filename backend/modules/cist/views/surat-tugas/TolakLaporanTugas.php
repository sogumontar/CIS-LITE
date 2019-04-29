<?php 
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Delete Instansi';
$this->params['header'] = 'Tolak Laporan Surat Tugas';

$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas', 'url'=> ['index-wr']];
$this->params['breadcrumbs'][] = "Tolak Laporan";

//TODO: buat ui helper untuk menampilkan form konfirmasi

 ?>
 <div class="box box-solid">
 	<div class="box-header">
 		<i class="fa fa-warning"></i>
 		<h3 class="box-title">Warning</h3>
 	</div>
 	<div class="box-body">
 		<div class="alert alert-danger">
 			<i class="fa fa-ban"></i>
 			<p>Lanjutkan tolak laporan surat tugas ?</p>
 		</div>
	 	<div class="text-center">
	 		<a href="<?=Url::toRoute(['index-wr']) ?>" class="btn btn-sm btn-warning">Batal</a>
	 		<a href="<?=Url::toRoute(['tolak-laporan', 'id'=>$id]) ?>" class="btn btn-sm btn-danger">Lanjutkan</a>
	 	</div>
 	</div>
 </div>