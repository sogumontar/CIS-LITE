<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\RiwayatPendidikan */
$uiHelper=\Yii::$app->uiHelper;
$this->title = 'Tambah Riwayat Pendidikan';

if(!empty($modelPegawai->dosen)){
	$this->params['breadcrumbs'][] = ['label' => 'Dosen', 'url' => Url::toRoute(['dosen/index'])];
	$this->params['breadcrumbs'][] = ['label' => $modelPegawai->nama, 'url' => Url::toRoute(['dosen/view', 'id' => $modelPegawai->dosen->dosen_id])];
}
elseif(!empty($modelPegawai->staf)){	
	$this->params['breadcrumbs'][] = ['label' => 'Staf', 'url' => Url::toRoute(['staf/index'])];
	$this->params['breadcrumbs'][] = ['label' => $modelPegawai->nama, 'url' => Url::toRoute(['dosen/view', 'id' => $modelPegawai->staf->staf_id])];

}

$this->params['breadcrumbs'][] = ['label' => 'Riwayat Pendidikan'];
$this->params['breadcrumbs'][] = 'Tambah';
?>
<div class="riwayat-pendidikan-add">
	<?=$uiHelper->beginContentRow() ?>
		<?= $uiHelper->beginContentBlock(['id' => 'riwayat-pendidikan-form',]) ?>
			<h1><?= Html::encode($this->title) ?></h1>

		    <?= $this->render('_form', [
		        'model' => $model,
		    ]) ?>
		<?=$uiHelper->endContentBlock()?>
	<?=$uiHelper->endContentRow() ?>

</div>
