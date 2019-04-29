<?php 
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Reset Penghuni kamar';
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['asrama/index']];
$this->params['breadcrumbs'][] = ['label' => 'Asrama '.$asrama->name, 'url' => ['asrama/view-detail-asrama', 'id' => $asrama->asrama_id]];
$this->params['breadcrumbs'][] = ['label' => 'Daftar Kamar', 'url' => ['kamar/index', 'KamarSearch[asrama_id]' => $asrama->asrama_id, 'id_asrama' => $asrama->asrama_id]];
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
 			<p>Anda akan mengkosongkan semua kamar.</p>
 			<p>Apakah anda ingin melanjutkan ?</p>
 		</div>
	 	<div class="text-center">
	 		<a href="<?=Url::toRoute(['index', 'KamarSearch[asrama_id]' => $_GET['asrama_id'], 'id_asrama' => $_GET['asrama_id']]) ?>" class="btn btn-sm btn-warning">Cancel</a>
	 		<a href="<?=Url::toRoute(['reset-all-kamar', 'asrama_id' => $asrama_id, 'confirm' => 1, 'id_asrama' => $_GET['asrama_id']]) ?>" class="btn btn-sm btn-danger">Confirm Reset</a>
	 	</div>
 	</div>
 </div>