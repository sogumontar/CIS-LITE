<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\cist\models\KuotaCutiTahunan */

$this->title = $model->kuota_cuti_tahunan_id;
$this->params['breadcrumbs'][] = ['label' => 'Kuota Cuti Tahunans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kuota-cuti-tahunan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->kuota_cuti_tahunan_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->kuota_cuti_tahunan_id], [
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
            [
                'attribute' => 'pegawai_id',
                'label' => 'Pegawai_ID',
            ],
            'kuota'
        ],
    ]) ?>

</div>
