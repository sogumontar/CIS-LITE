<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\rakx\models\StrukturJabatanHasMataAnggaran */

$this->title = $model->strukturJabatan->jabatan.' - '.$model->tahunAnggaran->tahun;
$this->params['breadcrumbs'][] = ['label' => 'Penugasan Anggaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="struktur-jabatan-has-mata-anggaran-view">

    <p>
        <?= Html::a('Update', ['struktur-jabatan-has-mata-anggaran-edit', 'id' => $model->struktur_jabatan_has_mata_anggaran_id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            /*[
                'attribute' => 'struktur_jabatan_id',
                'value' => $model->strukturJabatan->jabatan,
                'label' => 'Jabatan',
            ],
            [
                'attribute' => 'tahun_anggaran_id',
                'value' => $model->tahunAnggaran->tahun,
                'label' => 'Tahun Anggaran',
            ],*/
            [
                'attribute' => 'mata_anggaran_id',
                'value' => $model->mataAnggaran->name,
                'label' => 'Mata Anggaran',
            ],
            [
                'attribute' => 'subtotal',
                'value' => function($model){
                    return "Rp".number_format($model->subtotal,2,',','.');
                },
            ],
            'desc:ntext',
        ],
    ]) ?>

</div>
