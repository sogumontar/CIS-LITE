<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinPengampu */

$this->title = $model->id_izin_pengampu;
$this->params['breadcrumbs'][] = ['label' => 'Izin Pengampus', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="izin-pengampu-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_izin_pengampu], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_izin_pengampu], [
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
            'id_izin_pengampu',
            'izin_keluar_id',
            'dosen_id',
            'status_request_id',
            'deleted',
            'deleted_at',
            'deleted_by',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>

</div>
