<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\Pegawai */


$this->title = $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Pegawai', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$uiHelper=\Yii::$app->uiHelper;
?>
<div class="pegawai-view">

    <?= $uiHelper->renderContentSubHeader("Data Profile", ['icon' => 'fa fa-menu']);?>
    <?= $uiHelper->renderLine(); ?>
    <?= $uiHelper->beginContentBlock(['id' => 'grid-menu-pegawai',
            //'width' => 4,
        ]); ?>

    <p>
        <div class="pull-right">
            <?=$uiHelper->renderButtonSet([
                'template' => ['edit'],
                'buttons' => [
                    'edit' => ['url' => Url::toRoute(['pegawai/edit', 'id'=> $model->pegawai_id]), 'label' => 'Edit', 'icon' => 'fa fa-pencil'],
                ]
            ]) ?>
        </div>
    </p>
    <?= $uiHelper->endContentBlock(); ?>
    <?= $uiHelper->beginContentBlock(['id' => 'grid-data-profile',
            //'width' => 4,
        ]); ?>
        <?= DetailView::widget([
        'model' => $model,
        'options' =>[
            'class' => 'table table-condensed detail-view'
        ],
        'attributes' => [
            //'pegawai_id',
            'nama',
            //'alias',
            'tempat_lahir',
            'tgl_lahir',
            'agama.nama',
            'jenisKelamin.desc', 
            'golonganDarah.nama',
            'telepon',
            'alamat',
            'kecamatan',
            'kabupaten.nama',
            'kode_pos',
            'no_ktp',
            'statusMarital.desc',
        ],
    ]) ?>
    <?= $uiHelper->endContentBlock(); ?>

    <?= $uiHelper->renderContentSubHeader("Data Kepegawaian", ['icon' => 'fa fa-menu']);?>
    <?= $uiHelper->renderLine(); ?>
    <?= $uiHelper->beginContentBlock(['id' => 'grid-data-kepegawaian',
            //'width' => 4,
        ]); ?>
        <?= DetailView::widget([
        'model' => $model,
        'options' =>[
            'class' => 'table table-condensed detail-view'
        ],
        'attributes' => [
            //'pegawai_id',
            'nip',
            //'nama',
            'alias',
            [
                'label' => "Status Ikatan Kerja Pegawai",
                'attribute' => 'statusIkatanKerjaPegawai.nama',

            ],
            [
                'label' => "Status Aktif Pegawai",
                'attribute' => 'statusAktifPegawai.desc',
                //'label' => '',
            ],
            // [
            //     'label' => 'Role',
            //     'attribute' => 'pegawai_id',
                
            // ],
            'tanggal_masuk',
            'tanggal_keluar',
        ],
    ]) ?>
    <?= $uiHelper->endContentBlock(); ?>

</div>
