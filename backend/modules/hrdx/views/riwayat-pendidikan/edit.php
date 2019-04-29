<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\RiwayatPendidikan */

$this->title = 'Ubah Riwayat Pendidikan';

if(!empty($model->pegawai->dosen)){
	$this->params['breadcrumbs'][] = ['label' => 'Dosen', 'url' => Url::toRoute(['dosen/index'])];
	$this->params['breadcrumbs'][] = ['label' => $model->pegawai->nama, 'url' => Url::toRoute(['dosen/view', 'id' => $model->pegawai->dosen->dosen_id])];
}
elseif(!empty($model->pegawai->staf)){	
	$this->params['breadcrumbs'][] = ['label' => 'Staf', 'url' => Url::toRoute(['staf/index'])];
	$this->params['breadcrumbs'][] = ['label' => $model->pegawai->nama, 'url' => Url::toRoute(['dosen/view', 'id' => $model->pegawai->staf->staf_id])];

}
$this->params['breadcrumbs'][] = ['label' => 'Riwayat Pendidikan'];
$this->params['breadcrumbs'][] = 'Ubah';
?>
<div class="riwayat-pendidikan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
