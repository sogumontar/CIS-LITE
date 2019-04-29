<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\AtasanIzin */

$this->title = $model->atasan_izin_id;
$this->params['breadcrumbs'][] = ['label' => 'Atasan Izins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="atasan-izin-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->atasan_izin_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->atasan_izin_id], [
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
            'atasan_izin_id',
            'pmhnn_izin_id',
            'pegawai_id',
            'nama',
            'deleted',
            'deleted_at',
            'deleted_by',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
