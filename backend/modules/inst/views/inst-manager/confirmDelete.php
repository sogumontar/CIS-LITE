<?php 
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Delete Instansi';
$this->params['header'] = 'Delete Instansi '.$name;

$this->params['breadcrumbs'][] = ['label' => 'Instansi Manager', 'url'=> ['index']];
$this->params['breadcrumbs'][] = "Delete";

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
 			<p>Delete Instansi bersifat Cascade Delete, semua Struktur Organisasi, Unit, dan Pejabat akan ikut terhapus</p>
 			<p>Apakah anda ingin melanjutkan ?</p>
 		</div>
	 	<div class="text-center">
	 		<a href="<?=Url::toRoute(['index']) ?>" class="btn btn-sm btn-warning">Cancel</a>
	 		<a href="<?=Url::toRoute(['instansi-del', 'id'=>$id, 'confirm' => 1]) ?>" class="btn btn-sm btn-danger">Confirm Delete</a>
	 	</div>
 	</div>
 </div>