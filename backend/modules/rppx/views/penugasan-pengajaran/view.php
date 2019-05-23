<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\rppx\models\PenugasanPengajaran */

$this->title = $model->penugasan_pengajaran_id;
$this->params['breadcrumbs'][] = ['label' => 'Penugasan Pengajarans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['layout'] = 'full';
?>
<div class="penugasan-pengajaran-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php echo $model->penugasan_pengajaran_id;?> 
        <?= Html::a('Update', ['update', 'id' => $model->penugasan_pengajaran_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->penugasan_pengajaran_id], [
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
            'penugasan_pengajaran_id',
            'pengajaran_id',
            'pegawai_id',
            'role_pengajar_id',
            'is_fulltime:datetime',
            'start_date',
            'end_date',
            'deleted',
            'deleted_by',
            'deleted_at',
            'created_at',
            'created_by',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
