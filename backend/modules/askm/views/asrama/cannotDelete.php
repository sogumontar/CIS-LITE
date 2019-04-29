<?php 
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Hapus Asrama';
$this->params['header'] = 'Hapus Asrama';

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
 			<p>Asrama tidak bisa dihapus karena memiliki penghuni!!</p>
 		</div>
 		<div class="text-center">
	 		<a href="<?=Url::toRoute(['index']) ?>" class="btn btn-sm btn-warning">Cancel</a>
	 	</div>
 	</div>
 </div>