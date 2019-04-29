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

$this->title = 'Daftar Inventori';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="barang-index">
<?php
    foreach ($dataProviders as $value) {
?>
<?=$uiHelper->renderContentSubHeader('Unit: '.$value['unit']);?>
<?=$uiHelper->renderLine();?>
     <div class="pull-right">
         <?=$uiHelper->renderButtonSet([
            'template' => ['tambah'],
            'buttons' => [
                'tambah'=>['url'=>Url::to(['barang/barang-add', 'unit_id'=>$value['unit_id']]), 'label'=>'Tambah Barang', 'icon'=>'fa fa-plus'],
            ]  
         ]) ?>
     </div> 
<?php Pjax::begin(); ?>
    <?= GridView::widget([
            'dataProvider' => $value['dataProvider'],
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute'=>'nama_barang',
                    'label'=>'Nama Barang',
                    'format'=>'raw',
                    'value'=>function ($model){
                            return "<a href='".Url::toRoute(['/invt/barang/barang-view', 'barang_id' => $model->barang_id])."'>".$model->nama_barang."</a>";
                    }
                ],
                [
                    'label'=>'Jenis',
                    'attribute'=>'jenis_barang_id',
                    'filter'=>ArrayHelper::map($_jenis,'jenis_barang_id','nama'),
                    'value'=>'jenisBarang.nama',
                ],
                [
                    'label'=>'Kategori',
                    'attribute'=>'kategori_id',
                    'filter'=>ArrayHelper::map($_kategori,'kategori_id','nama'),
                    'value'=>'kategori.nama',
                ],
               [
                    'label'=>'Total Jumlah',
                    'attribute'=>'jumlah',
                    'value'=>'jumlah',
                    'contentOptions'=>['class'=>'col-xs-1']
                ],
                [
                    'label'=>'Distribusi',
                    'value'=>function($model){
                        return $model->summaryJumlah==null?0:$model->summaryJumlah->jumlah_distribusi;
                    },
                ],
                [
                    'label'=>'Pinjam',
                    'value'=>function($model){
                        return $model->summaryJumlah==null?0:$model->summaryJumlah->jumlah_pinjam;
                    },
                ],
                [
                    'label'=>'Rusak',
                    'value'=>function($model){
                        return $model->summaryJumlah==null?0:$model->summaryJumlah->jumlah_rusak;
                    },
                ],
                [
                'class' => 'common\components\ToolsColumn',
                'template' => '{edit} {del}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action === 'edit') {
                        return Url::to(['barang-edit','barang_id'=>$key, 'unit_id'=>$model->unit_id]);
                    }elseif ($action === 'del') {
                        return Url::to(['barang-del','barang_id'=>$key, 'unit_id'=>$model->unit_id]);
                    }
                },
                'contentOptions'=>['class'=>'col-xs-1']
                ],
            ],
        ]); ?>
<?php Pjax::end(); ?>
<?php   
    }
?>
    
</div>
