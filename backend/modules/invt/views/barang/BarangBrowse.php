<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\BarangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Seluruh Inventori';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="barang-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'nama_barang',
                'label'=>'Nama',
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
                'label'=>'Unit',
                'attribute'=>'unit_id',
                'filter'=>ArrayHelper::map($_unit,'unit_id','nama'),
                'value'=>'unit.nama',
            ],
            [
                'label'=>'Total Jumlah',
                'attribute'=>'jumlah',
                'value'=>'jumlah',
                'contentOptions'=>['class'=>'col-xs-1']
            ],
        ],
    ]); ?>
</div>
