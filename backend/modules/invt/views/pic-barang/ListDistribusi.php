<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\components\ToolsColumn;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
$uiHelper = Yii::$app->uiHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\invt\models\search\PicBarangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List Distribusi Unit: '.$_namaUnit;
$this->params['breadcrumbs'][] = ['label'=>'Distribusi Barang','url'=>['pengeluaran-barang/barang-keluar-browse']];
$this->params['breadcrumbs'][] = 'Assign PIC Barang';
$this->params['header'] = $this->title;
?>
<div class="list-distribusi">
<p>Silahkan pilih barang yang akan di-assign.</p>
<br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options'=>['class'=>'table table-condensed'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>'Barang',
                'value'=>'barang.nama_barang',
            ],
            'kode_inventori',
            'tgl_keluar',
            [
                'label'=>'Lokasi',
                'filter'=>ArrayHelper::map($_lokasi,'lokasi_id','nama_lokasi'),
                'attribute'=>'lokasi_id',
                'value'=>'lokasi.nama_lokasi',
            ],
            'status_akhir',
            [
                'class' => 'common\components\ToolsColumn',
                'template' => '{assign}',
                'buttons'=>[
                    'assign'=>function($url,$model){
                        return ToolsColumn::renderCustomButton($url,$model,'Assign PIC',"fa fa-check");
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                   if ($action === 'assign') {
                        return Url::to(['pic-barang/pic-barang-add', 'pengeluaran_barang_id'=>$key, 'unit_id'=>$model->unit_id]);
                    }
                },
                'contentOptions'=>['class'=>'col-xs-1']
            ],
        ],
    ]); ?>
</div>
