
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

use common\helpers\LinkHelper;
/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\Dosen */

$this->title = "Data Diri";
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = 'Data Diri';
// echo "<pre>";
// var_dump($dosen);
// die;
$uiHelper=\Yii::$app->uiHelper;

?>

<?= $uiHelper->beginContentRow()?>
    <?= $uiHelper->beginContentBlock(['id' => 'grid-menu-pegawai',]); ?>
    <?php
        if(!empty($staf)){
    ?>
        <?= $uiHelper->beginTab([
        'header' => 'Data Diri',
        'icon' => 'fa fa-user',
        'tabs' => 

                [
                    ['id' => 'tab_1', 'label' => 'Data Kepegawaian', 'isActive' => true],
                    ['id' => 'tab_2', 'label' => 'Data Staf', 'isActive' => false],
                    ['id' => 'tab_3', 'label' => 'Riwayat Pendidikan', 'isActive' => false],
                    ['id' => 'tab_4', 'label' => 'Riwayat Pengajaran', 'isActive' => false], 
                ]
        ]) ?>
    <?php
        }elseif(!empty($dosen)){
    ?>
        <?= $uiHelper->beginTab([
        'header' => 'Data Diri',
        'icon' => 'fa fa-user',
        'tabs' => 

                [
                    ['id' => 'tab_1', 'label' => 'Data Kepegawaian', 'isActive' => true],
                    ['id' => 'tab_2', 'label' => 'Data Dosen', 'isActive' => false],
                    ['id' => 'tab_3', 'label' => 'Riwayat Pendidikan', 'isActive' => false],
                    ['id' => 'tab_4', 'label' => 'Riwayat Pengajaran', 'isActive' => false], 
                    ['id' => 'tab_5', 'label' => 'Publikasi', 'isActive' => false], 
                ]
        ]) ?>
    <?php
        }
    ?>
    

    	<?= $uiHelper->beginTabContent(['id'=>'tab_1', 'isActive' => true]) ?>
    		<div class="pegawai-view">

            <?= $uiHelper->renderContentSubHeader("Data Profile", ['icon' => 'fa fa-menu']);?>
            <?= $uiHelper->renderLine(); ?>
            <?= $uiHelper->beginContentBlock(['id' => 'grid-menu-pegawai',]); ?>

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
                            'tanggal_masuk',
                            'tanggal_keluar',
                        ],
                    ]) ?>

            </div>
    	<?= $uiHelper->endTabContent() ?>

        <?php
            if(!empty($dosen)){
        ?>
             <?= $uiHelper->beginTabContent(['id'=>'tab_2', 'isActive' => false]) ?>

                <div id="app-container">
                  <?= $uiHelper->renderContentSubHeader("Data Dosen") ?>
                  <?= $uiHelper->renderLine(); ?>

                    <?= DetailView::widget([
                        'model' => $dosen,
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


                <?= $uiHelper->beginTabContent(['id'=>'tab_3', 'isActive' => false]) ?>

                    <div id="app-container">
                        <?= $uiHelper->renderContentSubHeader("Riwayat Pendidikan") ?>
                            <div class="pull-right">
                                    <?=$uiHelper->renderButtonSet([
                                        'template' => ['add'],
                                        'buttons' => [
                                            'add' => ['url' => Url::toRoute(['riwayat-pendidikan/add', 'id'=>$model->pegawai_id]), 'label' => 'Add', 'icon' => 'fa fa-plus'],
                                        ]
                                    ]) ?>
                            </div>
                            <br>
                        <?= $uiHelper->renderLine(); ?>
                        <?php
                            foreach($dosen->riwayatPendidikan as $key => $value){
                        ?>
                        <div class="pull-right">
                            <?=$uiHelper->renderButtonSet([
                                'template' => ['add','edit', 'del' ],
                                'buttons' => [
                                    'add' => ['url' => Url::toRoute(['riwayat-pendidikan/add', 'id'=>$dosen->dosen_id]), 'label' => 'Add', 'icon' => 'fa fa-plus'],
                                    'edit' => ['url' => Url::toRoute(['riwayat-pendidikan/edit', 'id'=>$value->riwayat_pendidikan_id]), 'label' => 'Edit', 'icon' => 'fa fa-pencil'],
                                    'del' => ['url' => Url::toRoute(['riwayat-pendidikan/del', 'id'=>$value->riwayat_pendidikan_id]), 'label' => 'Delete', 'icon' => 'fa fa-trash'],

                                ]
                            ]) ?>
                        </div>
                            <?php echo DetailView::widget([
                                'model' => $value,
                                'attributes' => [
                                    [
                                        'label' => 'Jenjang',
                                        'attribute' => 'jenjangs.nama',
                                    ],
                                    'universitas',
                                    'jurusan',
                                    'judul_ta',
                                    'ipk',
                                    'thn_mulai',
                                    'thn_selesai',
                                ]

                            ]) ?>
                            
                            <br>
                        <?php
                           }
                        ?>
                    </div>
                <?= $uiHelper->endTabContent() ?>
 

                <?= $uiHelper->beginTabContent(['id'=>'tab_4', 'isActive' => false]) ?>

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
                                            if($data['nama_kul_ind'] !== null){
                                                return LinkHelper::renderLink(['label'=> $data['nama_kul_ind'], 'url'=>Url::to(['/prkl/perkuliahan/view','kuliah_id'=> $data['kuliah_id'], 'ta'=> $data['ta'] , 'sem_ta'=> $data['sem_ta']])]);
                                            }
                                            else{
                                                return "Tidak ada";
                                            }
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

                    <?= $uiHelper->beginTabContent(['id'=>'tab_5', 'isActive' => false]) ?>
                        <?= $uiHelper->renderContentSubHeader("Publikasi") ?>
                        <?= $uiHelper->renderLine(); ?>
                        <?= GridView::widget([
                        'dataProvider' => $_providerPublikasi,
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
        <?php
            }elseif(!empty($staf)){
        ?>
            <?= $uiHelper->beginTabContent(['id'=>'tab_2', 'isActive' => false]) ?>

                <div id="app-container">
                  <?= $uiHelper->renderContentSubHeader("Data Staf") ?>
                  <?= $uiHelper->renderLine(); ?>

                    <?= DetailView::widget([
                        'model' => $staf,
                        'attributes' => [
                            [
                                'label' => 'Nama',
                                'attribute' => 'pegawai.nama',
                            ],
                            
                            [
                                'label' => 'Posisi',
                                'attribute' => 'stafRole.nama'
                            ],
                            'aktif_start',
                            'aktif_end',
                        ],
                    ]) ?>
                </div>

                <?= $uiHelper->endTabContent() ?>


                <?= $uiHelper->beginTabContent(['id'=>'tab_3', 'isActive' => false]) ?>

                    <div id="app-container">
                        <?= $uiHelper->renderContentSubHeader("Riwayat Pendidikan") ?>
                        <?= $uiHelper->renderLine(); ?>

                        <?php
                            foreach($staf->riwayatPendidikan as $key => $value){
                        ?>
                        <div class="pull-right">
                            <?=$uiHelper->renderButtonSet([
                                'template' => ['add','edit', 'del' ],
                                'buttons' => [
                                    'add' => ['url' => Url::toRoute(['riwayat-pendidikan/add', 'id'=>$staf->staf_id]), 'label' => 'Add', 'icon' => 'fa fa-plus'],
                                    'edit' => ['url' => Url::toRoute(['riwayat-pendidikan/edit', 'id'=>$value->riwayat_pendidikan_id]), 'label' => 'Edit', 'icon' => 'fa fa-pencil'],
                                    'del' => ['url' => Url::toRoute(['riwayat-pendidikan/del', 'id'=>$value->riwayat_pendidikan_id]), 'label' => 'Delete', 'icon' => 'fa fa-trash'],

                                ]
                            ]) ?>
                        </div>
                            <?php echo DetailView::widget([
                                'model' => $value,
                                'attributes' => [
                                    [
                                        'label' => 'Jenjang',
                                        'attribute' => 'jenjangs.nama',
                                    ],
                                    'universitas',
                                    'jurusan',
                                    'judul_ta',
                                    'ipk',
                                    'thn_mulai',
                                    'thn_selesai',
                                ]

                            ]) ?>
                            
                            <br>
                        <?php
                           }
                        ?>
                    </div>
                <?= $uiHelper->endTabContent() ?>
             <?= $uiHelper->beginTabContent(['id'=>'tab_4', 'isActive' => false]) ?>

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
                                            return LinkHelper::renderLink(['label'=> $data['nama_kul_ind'], 'url'=>Url::to(['/prkl/perkuliahan/view','kuliah_id'=> $data['kuliah_id'], 'ta'=> $data['ta'] , 'sem_ta'=> $data['sem_ta']])]); 
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
        <?php
            }
        ?>
    <?=$uiHelper->endContentBlock()?>

<?= $uiHelper->endContentRow()?>