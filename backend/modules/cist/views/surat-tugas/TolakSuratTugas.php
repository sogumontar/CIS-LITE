<?php 
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Delete Instansi';
$this->params['header'] = 'Tolak Surat Tugas';

$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas Bawahan', 'url'=> ['index-surat-bawahan']];
$this->params['breadcrumbs'][] = "Tolak Surat Tugas";

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
 			<p>Lanjutkan tolak surat tugas ?</p>
 		</div>
	 	<div class="text-center">
	 		<a href="<?=Url::toRoute(['index-surat-bawahan']) ?>" class="btn btn-sm btn-warning">Batal</a>
	 		<a href="<?=Url::toRoute(['tolak', 'id' => $id]) ?>" class="btn btn-sm btn-danger">Lanjutkan</a>
	 	</div>
 	</div>
 </div>