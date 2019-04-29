<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\GolonganKuotaCuti */

$this->title = $model->nama_golongan;
$this->params['breadcrumbs'][] = ['label' => 'Golongan Kuota Cuti', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="golongan-kuota-cuti-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['edit', 'id' => $model->golongan_kuota_cuti_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['del', 'id' => $model->golongan_kuota_cuti_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nama_golongan',
            'min_tahun_kerja',
            'max_tahun_kerja',
            'kuota',
        ],
    ]) ?>

</div>
