<?php 
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Delete Struktur Jabatan';
if($otherRenderer){
    $this->params['breadcrumbs'][] = ['label' => 'Instansi Manager', 'url' => ['inst-manager/index']];
    $this->params['breadcrumbs'][] = ['label' => $instansi_name, 'url' => ['inst-manager/strukturs?instansi_id='.$model->instansi_id]];
}else
    $this->params['breadcrumbs'][] = ['label' => 'Struktur Jabatan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->jabatan, 'url' => ['struktur-jabatan-view', 'id' => $model->struktur_jabatan_id, 'otherRenderer' => $otherRenderer]];
$this->params['breadcrumbs'][] = 'Delete';

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
 			<p>Delete Struktur Jabatan bersifat Cascade Delete, semua Struktur Jabatan beserta Pejabat di bawahnya akan ikut terhapus</p>
 			<p>Apakah anda ingin melanjutkan ?</p>
 		</div>
	 	<div class="text-center">
	 	<?php if($otherRenderer){ ?>
	 		<a href="<?=Url::toRoute(['inst-manager/strukturs?instansi_id='.$model->instansi_id]) ?>" class="btn btn-sm btn-warning">Cancel</a>
	 	<?php } else { ?>
	 		<a href="<?=Url::toRoute(['index']) ?>" class="btn btn-sm btn-warning">Cancel</a>
	 	<?php } ?>
	 		<a href="<?=Url::toRoute(['struktur-jabatan-del', 'id'=>$id, 'otherRenderer' => $otherRenderer, 'confirm' => 1]) ?>" class="btn btn-sm btn-danger">Confirm Delete</a>
	 	</div>
 	</div>
 </div>