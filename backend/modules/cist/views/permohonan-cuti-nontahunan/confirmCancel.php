<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Batalkan Request';
$this->params['breadcrumbs'][] = ['label' => 'Permohonan Izin', 'url' => ['index-by-staf']];
$this->params['breadcrumbs'][] = $this->title;

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
 			<p>Anda akan membatalkan request anda</p>
 			<p>Apakah anda ingin melanjutkan?</p>
 		</div>
	 	<div class="text-center">
	 		<a href="<?=Url::toRoute(['index-by-staf']) ?>" class="btn btn-sm btn-warning">Cancel</a>
	 		<a href="<?=Url::toRoute(['cancel-by-staf', 'id' => $_GET['id'], 'confirm' => 1]) ?>" class="btn btn-sm btn-danger">Confirm</a>
	 	</div>
 	</div>
 </div>
