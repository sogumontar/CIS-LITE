<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\Kamar */

$this->title = $model->nomor_kamar.' - '.$model->asrama['name'];
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['asrama/index']];
$this->params['breadcrumbs'][] = ['label' => 'Asrama '.$model->asrama['name'], 'url' => ['asrama/view-detail-asrama', 'id' => $model->asrama_id]];
$this->params['breadcrumbs'][] = ['label' => 'Daftar Kamar', 'url' => ['kamar/index', 'KamarSearch[asrama_id]' => $model->asrama_id, 'id_asrama' => $model->asrama_id]];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="kamar-view">

    <div class="pull-right">
        Pengaturan
        <?= $uiHelper->renderButtonSet([
                'template' => ['addMhs', 'reset', 'edit'],
                'buttons' => [
                    'addMhs' => ['url' => Url::toRoute(['dim-kamar/add-penghuni-kamar', 'id' => $model->kamar_id, 'id_asrama' => $model->asrama_id]), 'label'=> 'Tambah Penghuni', 'icon'=>'fa fa-users'],
                    'reset' => ['url' => Url::toRoute(['reset-kamar', 'id' => $model->kamar_id, 'id_asrama' => $model->asrama_id]), 'label'=> 'Reset Kamar', 'icon'=>'fa fa-refresh'],
                    'edit' => ['url' => Url::toRoute(['edit-kamar', 'id' => $model->kamar_id, 'id_asrama' => $model->asrama_id]), 'label'=> 'Edit Kamar', 'icon'=>'fa fa-pencil'],
                ],
                
            ]) ?>
    </div>

    <h1><?= $this->title ?></h1>
    <?= $uiHelper->renderLine(); ?>

    <h2>Penghuni : </h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // [
            //     'attribute' => 'dim_id',
            //     'label' => 'Nama',
            //     'value' => 'dim.nama'
            // ],
            [
                'attribute'=>'Nama Mahasiswa',
                'format' => 'raw',
                'value'=>function ($model) {
                    return "<a href='".Url::toRoute(['/dimx/dim/mahasiswa-view', 'dim_id' => $model->dim_id])."'>".$model->dim->nama."</a>";
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{del}',
                'contentOptions' => ['style' => 'width: 8.7%'],
                'buttons'=>[
                    'del'=>function ($url, $model) {
                        return Html::a('<i class="fa fa-times"></i> Hapus', ['dim-kamar/del-penghuni', 'id' => $model->dim_kamar_id, 'id_kamar' => $_GET['id']], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>
