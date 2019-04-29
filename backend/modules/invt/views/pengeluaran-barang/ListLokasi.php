<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\components\ToolsColumn;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\BarangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List Lokasi';
$this->params['breadcrumbs'][] = ['label'=>'Distribusi Barang', 'url'=>['pengeluaran-barang/barang-keluar-browse']];
$this->params['breadcrumbs'][] = 'Distribusi Barang berdasarkan Lokasi';
$this->params['header'] = $this->title;
?>
<div class="lokasi-index">
<p>Silahkan pilih lokasi tujuan distribusi barang.</p>
<br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options'=>['class'=>'table table-condensed'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'nama_lokasi',
                'label'=>'Tujuan Lokasi',
                'format'=>'raw',
                'value'=>function($model){
                    if($model->getChilds()!=null || $model->parent_id==0){
                        return "<strong>".$model->nama_lokasi."<strong>";
                    }
                    return $model->nama_lokasi;
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
                'class' => 'common\components\ToolsColumn',
                'template' => '{keluar}',
                'buttons'=>[
                    'keluar'=>function($url,$model){
                        return ToolsColumn::renderCustomButton($url,$model,'Distribusikan',"fa fa-share-square");
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                   if ($action === 'keluar') {
                        return Url::to(['pengeluaran-barang/distribusi-bylokasi', 'lokasi_id'=>$key, 'unit_id'=>isset($_GET['unit_id'])?$_GET['unit_id']:null]);
                    }
                },
                'contentOptions'=>['class'=>'col-xs-1']
            ],
        ],
    ]); ?>
</div>
