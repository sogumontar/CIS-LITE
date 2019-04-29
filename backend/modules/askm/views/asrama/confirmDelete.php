<?php 
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Hapus Asrama';
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['asrama/index']];
$this->params['breadcrumbs'][] = ['label' => 'Asrama '.$model->asrama['name'], 'url' => ['asrama/view-detail-asrama', 'id' => $model->asrama_id]];
$this->params['breadcrumbs'][] = ['label' => 'Daftar Kamar', 'url' => ['kamar/index', 'KamarSearch[asrama_id]' => $model->asrama_id, 'id_asrama' => $model->asrama_id]];
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
 			<p>Anda akan menghapus asrama</p>
 			<p>Apakah anda ingin melanjutkan ?</p>
 		</div>
	 	<div class="text-center">
	 		<a href="<?=Url::toRoute(['index']) ?>" class="btn btn-sm btn-warning">Cancel</a>
	 		<a href="<?=Url::toRoute(['del', 'asrama_id'=>$id, 'confirm' => 1]) ?>" class="btn btn-sm btn-danger">Confirm Delete</a>
	 	</div>
 	</div>
 </div>