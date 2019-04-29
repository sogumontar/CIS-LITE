<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\LinkHelper;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\modules\hrdx\models\Staf */
// echo "<pre>";
// var_dump($model);
// die;
$this->title = $model->pegawai->nama;
$this->params['breadcrumbs'][] = ['label' => 'Staf', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$uiHelper=\Yii::$app->uiHelper;
// echo "<pre>";
// var_dump($model->stafRole);
// die;
?>

<?= $uiHelper->renderLine(); ?>
<?php 
    $conf_ta = Yii::$app->appConfig->get('staf_role_asisten_dosen');
    if($model->stafRole !== NULL && $model->stafRole->nama == $conf_ta){

?>
    <?= $uiHelper->beginTab([
        'header' => $model->pegawai['nama'],
        'icon' => 'fa fa-user',
        'tabs' => [
            ['id' => 'tab_1', 'label' => 'Data Staf', 'isActive' => true],
            ['id' => 'tab_2', 'label' => 'Riwayat Pendidikan', 'isActive' => false],
            ['id' => 'tab_3', 'label' => 'Data Akademik', 'isActive' => false],
        ]
    ]) ?>
<?php
    }
    else
    {
?>
    <?= $uiHelper->beginTab([
        'header' => $model->pegawai['nama'],
        'icon' => 'fa fa-user',
        'tabs' => [
            ['id' => 'tab_1', 'label' => 'Data Staf', 'isActive' => true],
            ['id' => 'tab_2', 'label' => 'Riwayat Pendidikan', 'isActive' => false],
        ]
    ]) ?>

<?php
    }
?>

    <?= $uiHelper->beginTabContent(['id'=>'tab_1', 'isActive' => true]) ?>

    <div id="app-container">
      <?= $uiHelper->renderContentSubHeader("Data Staf") ?>
      <?= $uiHelper->renderLine(); ?>
       <p>


            <div class="pull-right">
                <?=$uiHelper->renderButtonSet([
                    'template' => ['edit'],
                    'buttons' => [
                        'edit' => ['url' => Url::to(['edit', 'id'=>$model->staf_id]), 'label' => 'Edit', 'icon' => 'fa fa-pencil'],
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
                [
                    'label' => 'Prodi',
                    'attribute' => 'prodi.kbk_ind',
                    'value' => function($data){ return isset($data->prodi->kbk_ind)?($data->prodi->jenjang->nama.'-'.$data->prodi->kbk_ind):''; }
                ],
                [
                    'label' => 'Posisi',
                   'attribute' => 'stafRole.nama',
                   
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
                            'add' => ['url' => Url::toRoute(['riwayat-pendidikan/add', 'id'=>$model->pegawai_id]), 'label' => 'Add', 'icon' => 'fa fa-plus'],
                        ]
                    ]) ?>
            </div>
            <br>
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
        <?php
            }
        ?>

    <?= $uiHelper->endTabContent() ?>

 <?= $uiHelper->endTab() ?>
