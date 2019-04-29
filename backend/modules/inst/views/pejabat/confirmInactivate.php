<?php 
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Nonaktifkan Pejabat';
$this->params['breadcrumbs'][] = ['label' => 'Management Pejabat', 'url' => ['index']];
if($renderer == 0){
	$this->params['breadcrumbs'][] = ['label' => $model->pegawai['nama'].' - '.$model->strukturJabatan['jabatan'], 'url' => ['pejabat-view', 'id' => $model->pejabat_id]];
}else if($renderer == 1){
	$this->params['breadcrumbs'][] = ['label' => $model->pegawai['nama'].' - '.$model->strukturJabatan['jabatan'], 'url' => ['pejabat-by-pegawai-view', 'pegawai_id' => $model->pegawai_id]];
}else if($renderer == 2){
	$this->params['breadcrumbs'][] = ['label' => $model->pegawai['nama'].' - '.$model->strukturJabatan['jabatan'], 'url' => ['pejabat-by-jabatan-view', 'jabatan_id' => $model->struktur_jabatan_id]];
}
$this->params['breadcrumbs'][] = 'Nonaktifkan';

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
 			<p>Nonaktifkan Pejabat bersifat 1 arah, sehingga tidak bisa dikembalikan.</p>
 			<p>Apakah anda ingin melanjutkan ?</p>
 		</div>
	 	<div class="text-center">
	 		<a href="<?=Url::to(\Yii::$app->request->referrer) ?>" class="btn btn-sm btn-warning">Cancel</a>
	 		<a href="<?=Url::toRoute(['pejabat-status-nonaktif-edit', 'id'=>$id, 'renderer' => $renderer, 'confirm' => 1]) ?>" class="btn btn-sm btn-danger">Confirm Inactivate</a>
	 	</div>
 	</div>
 </div>