<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\components\ToolsColumn;

$uiHelper = Yii::$app->uiHelper;
$this->title = "Detail Barang : ".$nama_lokasi;
$this->params['breadcrumbs'][] = ['label'=>'Distribusi Barang', 'url'=>['pengeluaran-barang/barang-keluar-browse']];
$this->params['breadcrumbs'][] = ['label'=>'Lokasi Barang', 'url'=>['pengeluaran-barang/lokasi-barang']];
$this->params['breadcrumbs'][] = $nama_lokasi ;
$this->params['header'] = $this->title;
?>

<?= $uiHelper->beginSingleRowBlock(['id'=>'detail-lokasi-barang'])?>
	<?= GridView::widget([
		'dataProvider'=>$dataProvider,
		'filterModel'=>$searchModel,
		'columns'=>[
			['class'=>'yii\grid\SerialColumn'],
			[
				'label'=>'Barang',
				'format'=>'raw',
				'value'=>function($model){
 						return "<a href='".Url::toRoute(['barang/barang-view', 'barang_id' => $model->barang_id])."'>".$model->barang->nama_barang."</a>";
				},
			],
			'kode_inventori',
			'barang.kategori.nama',
			'barang.jenisBarang.nama',
			'tgl_keluar',
			[
				'label'=>'Unit',
				'value'=>'detailUnit.nama',
			],
			
		]
	]);?>
<?= $uiHelper->endSingleRowBlock()?>