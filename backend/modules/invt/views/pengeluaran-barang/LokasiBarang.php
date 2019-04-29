<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\BarangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lokasi Barang: Seluruh Unit';
$this->params['breadcrumbs'][] = ['label'=>'Distribusi Barang', 'url'=>['pengeluaran-barang/barang-keluar-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="lokasi-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options'=>['class'=>'table table-condensed'],
         'rowOptions' => function ($model, $index, $widget, $grid){
          return ['height'=>'10'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'nama_lokasi',
                'label'=>'Lokasi',
                'format'=>'raw',
                'value'=>function($model){
                    if($model->getChilds()!=null || $model->parent_id==0){
                        return "<a href='".Url::toRoute(['/invt/pengeluaran-barang/barang-bylokasi', 'lokasi_id' => $model->lokasi_id])."'><strong>".$model->nama_lokasi."</strong></a>";
                    }
                    return "<a href='".Url::toRoute(['/invt/pengeluaran-barang/barang-bylokasi', 'lokasi_id' => $model->lokasi_id])."'>".$model->nama_lokasi."</a>";
                },
            ],
            [
                'label'=>'Parent Lokasi',
                'value'=>'detail.nama_lokasi',
            ],
            [
                'attribute'=>'desc',
                'label'=>'Deskripsi Lokasi',
                'value'=>'desc',
            ],
            [
                'label'=>'Total Barang',
                'value'=>function($model){
                    return $model->getJumlahBarang($model->lokasi_id);
                },
            ],
        ],
    ]); ?>
</div>
