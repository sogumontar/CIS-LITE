<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\helpers\LinkHelper;
use common\components\ToolsColumn;
use yii\helpers\Url;
use yii\widgets\Pjax;

$uiHelper = Yii::$app->uiHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\PemindahanBarangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Histori Pemindahan Barang';
$this->params['breadcrumbs'][] = ['label'=>'Distribusi Barang', 'url'=>['pengeluaran-barang/barang-keluar-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<?= $uiHelper->beginSingleRowBlock(['id'=>'pindah-barang-browse'])?>
<?php
    foreach ($dataProviders as$value) {
?>
<?=$uiHelper->renderContentSubHeader('Unit: '.$value['unit'])?>
<?=$uiHelper->renderLine()?>
     <div class="pull-right">
         <?=$uiHelper->renderButtonSet([
            'template' => ['pindah'],
            'buttons' => [
                'pindah' =>['url' => Url::to(['pemindahan-barang/pindah-barang','unit_id'=>$value['unit_id']]), 'label' => 'Tambah pindah barang', 'icon' => 'fa fa-exchange'],
            ]  
         ]) ?>
     </div> 
<?php Pjax::begin()?>
    <?= GridView::widget([
        'dataProvider' => $value['dataProvider'],
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>'Nama Barang',
                'format'=>'raw',
                'value'=>function($model)
                {
                   return LinkHelper::renderLink(['pjax' => false, 'label' => $model->pengeluaranBarang->barang->nama_barang, 
                                                    'url'=>Url::to(['barang/barang-view','barang_id'=>$model->pengeluaranBarang->barang_id]),
                                                    ]);
                }
            ],
            [
                'label'=>'Lokasi Akhir',
                'filter'=>ArrayHelper::map($lokasi,'lokasi_id','nama_lokasi'),
                'attribute'=>'lokasi_akhir_id',
                'value'=>'lokasiAkhir.nama_lokasi',
            ],
            'kode_inventori',
            [
                'label'=>'Lokasi Awal',
                'filter'=>ArrayHelper::map($lokasi,'lokasi_id','nama_lokasi'),
                'attribute'=>'lokasi_awal_id',
                'value'=>'lokasiAwal.nama_lokasi',

            ],
            'kode_inventori_awal',
            'tanggal_pindah',
            [
                'class' => 'common\components\ToolsColumn',
                'template' => '{detail}',
                'buttons' => [
                    'detail'=>function ($url,$model) {
                        return ToolsColumn::renderCustomButton($url, $model, 'Histori Pemindahan', 'fa fa-bars');
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action==='detail'){
                        return Url::to(['pemindahan-barang/detail-histori','pengeluaran_barang_id'=>$model->pengeluaran_barang_id]);
                    }
                },
                'contentOptions'=>['class'=>'col-xs-1']
            ],
        ],
    ]); ?>
<?php Pjax::end()?>
<?php
    }
?>
<?= $uiHelper->endSingleRowBlock()?>
