<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
use yii\widgets\Pjax;
$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\PengeluaranBarangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Distribusi Barang';
$this->params['breadcrumbs'][] = ['label'=>'Daftar Inventori', 'url'=>['barang/barang-browse-byadmin']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header']=$this->title;
?>
<div class="pengeluaran-barang-index">
<?php
    foreach ($dataProviders as $value) {
?>
<?=$uiHelper->renderContentSubHeader('Unit: '.$value['unit']);?>
<?=$uiHelper->renderLine();?>
     <div class="pull-right">
         <?=$uiHelper->renderButtonSet([
            'template' => ['distribusi'],
            'buttons' => [
                'distribusi' =>['url' => Url::to(['pengeluaran-barang/list-lokasi', 'unit_id'=>$value['unit_id']]), 'label' => 'Tambah Distribusi Barang', 'icon' => 'fa fa-mail-forward'],
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
                    'label'=>'Lokasi Awal Distribusi', 
                    'filter'=>ArrayHelper::map($_lokasi, 'lokasi_id', 'nama_lokasi'),
                    'attribute'=>'lokasi_distribusi',
                    'value'=>'lokasi.nama_lokasi',
                ],
                [
                    'label'=>'Tanggal Awal Distribusi',
                    'attribute'=>'tgl_keluar',
                ],
                [
                    'label'=>'Total Jumlah',
                    'value'=>'total_barang_keluar',
                ],
                'keterangan',
               [
                'class' => 'common\components\ToolsColumn',
                'template' => '{detail}{tambah}',
                'buttons' => [
                    'detail'=>function ($url,$model) {
                        return ToolsColumn::renderCustomButton($url, $model, 'Detail Distribusi', 'fa fa-bars');
                    },
                    'tambah'=>function($url,$model){
                        return ToolsColumn::renderCustomButton($url,$model, 'Tambah Barang','fa fa-plus');
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action==='detail'){
                        return Url::to(['pengeluaran-barang/detail-barang-keluar', 'keterangan_pengeluaran_id'=>$model->keterangan_pengeluaran_id]);
                    }elseif($action==='tambah'){
                        return Url::to(['pengeluaran-barang/tambah-barang-distribusi','keterangan_pengeluaran_id'=>$model->keterangan_pengeluaran_id]);
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
</div>