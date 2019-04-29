<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\JenisAbsen */

$this->title = $model->jenis_absen_id;
$this->params['breadcrumbs'][] = ['label' => 'Jenis Absens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jenis-absen-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->jenis_absen_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->jenis_absen_id], [
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
            'jenis_absen_id',
            'nama',
            'kuota',
            'deleted',
            'deleted_by',
            'deleted_at',
            'updated_by',
            'updated_at',
            'created_by',
            'created_at',
        ],
    ]) ?>

</div>
