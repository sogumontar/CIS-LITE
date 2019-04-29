<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

use common\helpers\LinkHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\Dosen */

$this->title = $model->pegawai['nama'];
$this->params['breadcrumbs'][] = ['label' => 'Dosen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper=\Yii::$app->uiHelper;

?>

<?= $uiHelper->beginTab([
        'header' => $model->pegawai['nama'],
        'icon' => 'fa fa-user',
        'tabs' => [
            ['id' => 'tab_1', 'label' => 'Data Dosen', 'isActive' => true],
            ['id' => 'tab_2', 'label' => 'Riwayat Pendidikan', 'isActive' => false],
            ['id' => 'tab_3', 'label' => 'Riwayat Pengajaran', 'isActive' => false],
            ['id' => 'tab_4', 'label' => 'Publikasi', 'isActive' => false],
        ]
    ]) ?>

    <?= $uiHelper->beginTabContent(['id'=>'tab_1', 'isActive' => true]) ?>

    <div id="app-container">
      <?= $uiHelper->renderContentSubHeader("Data Dosen") ?>
      <?= $uiHelper->renderLine(); ?>
        <p>
            <div class="pull-right">
                <?=$uiHelper->renderButtonSet([
                    'template' => ['edit' ],
                    'buttons' => [
                        'edit' => ['url' => Url::to(['edit', 'id'=>$model->dosen_id]), 'label' => 'Edit', 'icon' => 'fa fa-pencil'],
                    ]
                ]) ?>
            </div>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => 'Nama',
                    'attribute' => 'pegawai.nama',
                ],
                'nidn',
                [
                    'label' => 'Prodi',
                    'attribute' => 'prodi.kbk_ind',
                    'value' => function($data){ return isset($data->prodi->kbk_ind)?($data->prodi->jenjang->nama.'-'.$data->prodi->kbk_ind):''; }
                ],
                [
                    'label' => 'Jabatan Akademik',
                    'attribute' => 'jabatanAkademik.desc',
                ],
                [
                    'label' => 'Golongan Kepangkatan',
                    'attribute' => 'golonganKepangkatan.nama',
                ],
                [
                    'label' => 'Status Ikatan Kerja',
                    'attribute' => 'ikatanKerjaDosen.nama'
                ],
                'aktif_start',
                'aktif_end',
            ],
        ]) ?>
    </div>

    <?= $uiHelper->endTabContent() ?>


    <?= $uiHelper->beginTabContent(['id'=>'tab_2', 'isActive' => false]) ?>

    <div id="app-container">
        <?= $uiHelper->renderContentSubHeader("Riwayat Pendidikan") ?>
            <div class="pull-right">
                    <?=$uiHelper->renderButtonSet([
                        'template' => ['add'],
                        'buttons' => [
                            'add' => ['url' => Url::toRoute(['riwayat-pendidikan/add', 'id'=>$model->pegawai_id]), 'label' => 'Tambah Riwayat', 'icon' => 'fa fa-plus'],
                        ]
                    ]) ?>
            </div>
        <?= $uiHelper->renderLine(); ?>
        <?php
            foreach($model->riwayatPendidikan as $key => $value){
        ?>
            <div class="pull-right">    
                    <?=$uiHelper->renderButtonSet([
                        'template' => ['edit', 'del' ],
                        'buttons' => [
                            'edit' => ['url' => Url::toRoute(['riwayat-pendidikan/edit', 'id'=>$value->riwayat_pendidikan_id]), 'label' => 'Edit', 'icon' => 'fa fa-pencil'],
                            'del' => ['url' => Url::toRoute(['riwayat-pendidikan/del', 'id'=>$value->riwayat_pendidikan_id]), 'label' => 'Delete', 'icon' => 'fa fa-trash'],
                        ]
                    ]) ?>
            </div>
                <?= DetailView::widget([
                    'model' => $value,
                    'attributes' => [
                        'jenjangs.nama',
                        'universitas',
                        'jurusan',
                        'judul_ta',
                        'ipk',
                        'thn_mulai',
                        'thn_selesai',
                    ]

                ]) ?>
        <?php
            }   
        ?>
    </div>
    <?= $uiHelper->endTabContent() ?>



    <?= $uiHelper->beginTabContent(['id'=>'tab_3', 'isActive' => false]) ?>
    <div id="app-container">
        <?= $uiHelper->renderContentSubHeader("Riwayat Pengajaran") ?>
        <?= $uiHelper->renderLine(); ?>

         <?= $uiHelper->beginSingleRowBlock(['id' => 'grid-pengajaran',
            //'width' => 4,
            // 'header' => $this->title,
        ]) ?>

    <?php
    Pjax::begin();

    echo GridView::widget([
        // 'language' => 'id-ID',
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-condensed table-bordered'],
        'layout' => "{items}\n{pager}{summary}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Kode Matakuliah',
                'attribute' => 'kode_mk',
                // 'value' => 'pengajaran.kurikulum.kode_mk',
            ],
            [
                'label' => 'Nama Matakuliah',
                'attribute' => 'nama_kul_ind',
                'format' => 'raw',
                // 'value' => 'pengajaran.kurikulum.nama_kul_ind',
                'value'=> function($data){
                            return (is_null($data['nama_kul_ind'])?  '(NULL)': LinkHelper::renderLink(['label'=> $data['nama_kul_ind'], 'url'=>Url::to(['/prkl/perkuliahan/view','kuliah_id'=> $data['kuliah_id'], 'ta'=> $data['ta'] , 'sem_ta'=> $data['sem_ta']])])); 
                        },
            ],
            [
                'label' => 'Role Pengajar',
                'attribute' => 'role_pengajar',
                // 'value' => 'rolePengajar.nama',
            ],
            [
                'label' => 'Tahun Ajaran',
                'attribute' => 'ta',
                // 'value' => 'pengajaran.ta',
            ],
            [
                'label' => 'Semester',
                'attribute' => 'sem_ta',
                // 'value' => 'pengajaran.ta',
            ],
            
        ],
    ]); 
    Pjax::end()
    ?>
    <?=$uiHelper->endSingleRowBlock()?>
    </div>
    <?= $uiHelper->endTabContent() ?>

    <?= $uiHelper->beginTabContent(['id'=>'tab_4', 'isActive' => false]) ?>
        <?= $uiHelper->renderContentSubHeader("Publikasi") ?>
        <?= $uiHelper->renderLine(); ?>
        <?= GridView::widget([
        'dataProvider' => $publikasi,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'Judul',
                'format' => 'raw',
                'value'=>function ($data) {
                            return "<a href='".Url::toRoute(['/lppm/publikasi/view', 'id' => $data->publikasi_id])."'>".$data->judul."</a>";
                            },
            ],
            [
                'label' => 'Konferensi',
                'value' => 'konferensi',
                'contentOptions'=>['class'=>'col-xs-2']
            ],
            [
                'label' => 'Jenis Karya Ilmiah',
                'value' => 'subkaryailmiah.jenis',
                'contentOptions'=>['class'=>'col-xs-3'],
            ],
            [
                'header' => 'GBK',
                'attribute' => 'gbk.nama',
            ],
            'tanggal_publish',
        ],
    ]); ?>
    <?= $uiHelper->endTabContent() ?>

<?= $uiHelper->endTab() ?>
