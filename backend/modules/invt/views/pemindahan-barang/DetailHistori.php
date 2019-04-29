<?php
use yii\helpers\Html;
use yii\grid\GridView;

$uiHelper = Yii::$app->uiHelper;

$this->title = 'Detail Pemindahan Barang';
$this->params['breadcrumbs'][] = ['label'=>'Histori Pemindahan Barang', 'url'=>['pindah-barang-browse']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<?= $uiHelper->beginSingleRowBlock(['id'=>'detail-content'])?>
<?=GridView::widget([
	'dataProvider'=>$dataProvider,
	'columns'=>[
		['class'=>'yii\grid\SerialColumn'],
		[
			'label'=>'Barang',
			'value'=>'pengeluaranBarang.barang.nama_barang',
		],
		'tanggal_pindah',
		[
			'label'=>'Lokasi Akhir',
			'value'=>'lokasiAkhir.nama_lokasi',
		],
		'kode_inventori',
		[
			'label'=>'Lokasi Awal',
			'attribute'=>'lokasi_awal_id',
			'value'=>function($data){
				return $data->lokasi_awal_id == null?'Gudang':$data->lokasiAwal->nama_lokasi;
			}
		],
		[
			'label'=>'Kode Inventori Awal',
			'attribute'=>'kode_inventori_awal',
			'value'=>function($data){
				return $data->kode_inventori_awal == null?'-':$data->kode_inventori_awal;
			}
		],
		'status_transaksi',
	]
])?>
<?= $uiHelper->endSingleRowBlock()?>