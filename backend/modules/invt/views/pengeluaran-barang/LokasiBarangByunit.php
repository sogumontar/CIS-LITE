<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
use yii\widgets\Pjax;
$uiHelper = Yii::$app->uiHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\BarangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lokasi Barang berdasarkan Unit';
$this->params['breadcrumbs'][] = ['label'=>'Distribusi Barang', 'url'=>['pengeluaran-barang/barang-keluar-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="unit-index">
<?php
    foreach ($dataProviders as $key => $value) {
?>
<?=$uiHelper->renderContentSubHeader('Unit: '.$value['unit']);?>
<?=$uiHelper->renderLine();?>
<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $value['dataProvider'],
        'options'=>['class'=>'table table-condensed'],
         'rowOptions' => function ($model, $index, $widget, $grid){
          return ['height'=>'10'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'lokasi_id',
                'label'=>'Lokasi',
                'format'=>'raw',
                'value'=>function($model)use($value){
                    if($model->lokasi->getChilds()!=null || $model->lokasi->parent_id==0){
                        return "<a href='".Url::toRoute(['/invt/pengeluaran-barang/detail-byunitlokasi', 'unit_id'=>$value['unit_id'],'lokasi_id' => $model->lokasi_id])."'><strong>".$model->lokasi->nama_lokasi."</strong></a>";
                    }
                    return "<a href='".Url::toRoute(['/invt/pengeluaran-barang/detail-byunitlokasi', 'unit_id'=>$value['unit_id'], 'lokasi_id' => $model->lokasi_id])."'>".$model->lokasi->nama_lokasi."</a>";
                },
            ],
            [
                'label'=>'Parent Lokasi',
                'value'=>'lokasi.detail.nama_lokasi',
            ],
            [
                'attribute'=>'desc',
                'label'=>'Deskripsi Lokasi',
                'value'=>'lokasi.desc',
            ],
            [
                'label'=>'Jumlah Barang',
                'value'=>'jumlahBarang',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>
<?php
    }
?>
</div>
