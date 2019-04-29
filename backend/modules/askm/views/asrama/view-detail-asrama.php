<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\helpers\LinkHelper;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
use backend\modules\askm\models\Asrama;
use backend\modules\askm\models\Kamar;
use backend\modules\askm\models\Prodi;
use backend\modules\askm\models\Pegawai;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\Asrama */

$this->title = 'Asrama '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Asrama', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="asrama-view">

    <div class="pull-right">
        Pengaturan
        <?php
        echo $uiHelper->renderButtonSet([
            'template' => ['edit', 'keasramaan', 'penghuni' /*'kamar'*/ /* ,'import' */, 'export'],
            'buttons' => [
                'edit' => ['url' => Url::toRoute(['edit', 'id' => $model->asrama_id]), 'label'=> 'Edit Asrama', 'icon'=>'fa fa-pencil'],
                'keasramaan' => ['url' => Url::toRoute(['keasramaan/add-pengurus', 'id_asrama' => $model->asrama_id]), 'label'=> 'Tambah Pengurus', 'icon'=>'fa fa-user-secret'],
                'penghuni' => ['url' => Url::toRoute(['dim-kamar/add-penghuni', 'id_asrama' => $model->asrama_id]), 'label'=> 'Tambah Penghuni', 'icon'=>'fa fa-users'],
                // 'kamar' => ['url' => Url::toRoute(['kamar/index', 'KamarSearch[asrama_id]' => $model->asrama_id, 'id_asrama' => $model->asrama_id]), 'label'=> 'Daftar Kamar', 'icon'=>'fa fa-list'],
                // 'import' => ['url' => Url::toRoute(['dim-kamar/import-excel', 'asrama_id' => $model->asrama_id]), 'label'=> 'Import Data Penghuni', 'icon'=>'fa fa-download'],
                'export' => ['url' => Url::toRoute(['export-excel', 'asrama_id' => $model->asrama_id]), 'label'=> 'Export Data Penghuni', 'icon'=>'fa fa-upload'],
            ],

        ]);
        ?>
    </div>

    <h1><?= $this->title ?></h1>
    <hr/>

    <div class="row">
        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'name',
                        'label' => 'Nama'
                    ],
                    'lokasi',
                    [
                        'attribute' => 'desc',
                        'label' => 'Rincian',
                    ],
                    [
                        'attribute' => 'jumlah_mahasiswa',
                        'label' => 'Penghuni',
                    ],
                    'kapasitas',
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'layout' => '{items} {pager}',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'header' => 'Pengurus Asrama',
                        'attribute' => 'pegawai_id',
                        'value' => 'pegawai.nama',
                    ],

                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{del}',
                        'contentOptions' => ['style' => 'width: 8.7%'],
                        'buttons'=>[
                            'del'=>function ($url, $model) {
                                return Html::a('<i class="fa fa-trash"></i> Hapus', ['keasramaan/del-pengurus', 'id' => $model->keasramaan_id], [
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
    </div>

    <?= $uiHelper->renderLine(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider2,
        'filterModel' => $searchModel2,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'dim_nama',
                'label'=>'Nama Mahasiswa',
                'format' => 'raw',
                'value'=>function ($model) {
                    return "<a href='".Url::toRoute(['/dimx/dim/mahasiswa-view', 'dim_id' => $model->dim_id])."'>".$model->dim->nama."</a>";
                },
            ],
            [
                'attribute'=>'dim_prodi',
                'label' => 'Prodi',
                'filter'=>ArrayHelper::map(Prodi::find()->where('inst_prodi.deleted!=1')->andWhere("inst_prodi.kbk_ind NOT LIKE 'Semua Prodi'")->andWhere(['inst_prodi.is_hidden' => 0])->joinWith(['jenjangId' => function($query){
                    return $query->orderBy(['inst_r_jenjang.nama' => SORT_ASC]);
                }])->all(), 'ref_kbk_id', function($data){
                    if (is_null($data->jenjang_id)) {
                        return '';
                    } else{
                        return $data->kbk_ind;
                    }

                }, 'jenjangId.nama'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'value'=> function($model){
                    return $model->dim->kbkId==null?null:$model->dim->kbkId->jenjangId->nama." ".$model->dim->kbkId->kbk_ind;
                },
            ],
            [
                'attribute' => 'dim_angkatan',
                'label' => 'Angkatan',
                'headerOptions' => ['style' => 'width:20px'],
                'format' => 'raw',
                'value' => 'dim.thn_masuk',
            ],
            // [
            //     'attribute' => 'dim_dosen',
            //     'label' => 'Dosen Wali',
            //     'format' => 'raw',
            //     'value' => 'dim.registrasis.dosenWali.nama',
            // ],
            [
                'attribute'=>'nomor_kamar',
                'label' => 'Kamar',
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:80px'],
                'filter'=>ArrayHelper::map(Kamar::find()->where('deleted!=1')->andWhere(['asrama_id' => $_GET['id']])->asArray()->all(), 'kamar_id', 'nomor_kamar'),
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'ALL'],
                'value' => 'dim.dimAsrama.kamar.nomor_kamar',
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{move} {del}',
                // 'contentOptions' => ['style' => 'width: 8.7%'],
                'buttons'=>[
                    'del'=>function ($url, $model) {
                        return Html::a('<i class="fa fa-sign-out"></i> Keluar', ['dim-kamar/del-penghuni', 'id' => $model->dim_kamar_id, 'id_kamar' => $_GET['id']], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Apakah anda yakin ingin mengeluarkan penghuni?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                    'move'=>function ($url, $model) {
                        return Html::a('<i class="fa fa-arrow-right"></i>Pindah', ['dim-kamar/pilih-asrama', 'id' => $model->dim_kamar_id, 'id_kamar' => $_GET['id'], 'id_asrama' => $_GET['id']], [
                            'class' => 'btn btn-primary',
                            'data' => [
                                // 'confirm' => 'Apakah anda yakin ingin memindahkan penghuni?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
