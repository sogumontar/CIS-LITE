<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\DimPelanggaran */

$this->title = 'View Detail';
$this->params['breadcrumbs'][] = ['label' => 'Daftar Perilaku Mahasiswa/i', 'url' => ['dim-penilaian/index']];
$this->params['breadcrumbs'][] = ['label' => $dim, 'url' => ['dim-penilaian/view', 'id' => $_GET['penilaian_id']]];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
$date = $model->tanggal;
?>
<div class="dim-pelanggaran-view">

    <div class="pull-right">
        Pengaturan
        <?= $uiHelper->renderButtonSet([
                'template' => ['edit', 'pemutihan'],
                'buttons' => [
                    'edit' => ['url' => Url::toRoute(['edit', 'id' => $_GET['penilaian_id'], 'penilaian_id' => $model->penilaian_id]), 'label'=> 'Edit', 'icon'=>'fa fa-pencil'],
                    'pemutihan' => ['url' => Url::toRoute(['pemutihan', 'id' => $_GET['penilaian_id'], 'poin' => $model->pelanggaran_id]), 'label'=> 'Putihkan', 'icon'=>'fa fa-thumbs-up'],
                ],

            ]) ?>
    </div>

    <h1>Pelanggaran</h1>
    <?= $uiHelper->renderLine(); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'poin_id',
                'label' => 'Pelanggaran',
                'value' => $model->poin->name,
            ],
            [
                'attribute' => 'desc_pelanggaran',
                'label' => 'Ket. Pelanggaran',
                'value' => $model->desc_pelanggaran,
            ],
            [
                'attribute' => 'tanggal',
                'label' => 'Tgl. Pelanggaran',
                'value' => function($model){
                    return date('j M Y', strtotime($model->tanggal));
                },
            ],
            [
                'attribute' => 'pembinaan_id',
                'label' => 'Pembinaan',
                'value' => $model->pembinaan->name,
            ],
            [
                'attribute' => 'desc_pembinaan',
                'label' => 'Ket. Pembinaan',
                'value' => $model->desc_pembinaan,
            ],

        ],
    ]) ?>

</div>
