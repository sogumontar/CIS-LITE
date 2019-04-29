<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;


$uiHelper = Yii::$app->uiHelper;
$this->title = 'Detail Inventori';
$this->params['breadcrumbs'][] = ['label'=>'Distribusi Barang', 'url'=>['barang-keluar-browse']];
$this->params['breadcrumbs'][] = ['label'=>'Lokasi Barang by Unit','url'=>['lokasi-barang-byunit']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<div class="pull-right">
    <?=$uiHelper->renderButtonSet([
            'template' => ['cek','cetak'],
            'buttons' => [
            	'cek'=>['url'=>Url::to(['pengeluaran-barang/cek-inventori','unit_id'=>$unit_id,'lokasi_id'=>$lokasi_id]),'label'=>'Cek Inventori','icon'=>'fa fa-check'],
                'cetak'=>['url'=>Url::to(['pengeluaran-barang/cetak-byunitlokasi', 'unit_id'=>$unit_id, 'lokasi_id'=>$lokasi_id]), 'label'=>'Cetak Daftar Inventori', 'icon'=>'fa fa-print'],
            ]  
   ]) ?>
</div> 
<?= $uiHelper->beginSingleRowBlock(['id'=>'detail-inventori-unit']);?>
<div class="data-inventori">
	<table class="table">
		<tbody>
			<tr>
				<th class="col-md-2">Unit Inventori</th>
				<td><?=$unit?></td>
			</tr>
			<tr>
				<th class="col-md-2">Lokasi</th>
				<td><?=$lokasi?></td>
			</tr>
		</tbody>
	</table>
</div>

<?=GridView::widget([
	'dataProvider'=>$dataProvider,
	'columns'=>[
		['class'=>'yii\grid\SerialColumn'],
		[
			'label'=>'Nama Barang',
			'value'=>'barang.nama_barang'
		],
		[
			'label'=>'Kategori Barang',
			'value'=>'barang.kategori.nama',
		],
		[
			'label'=>'Jenis Barang',
			'value'=>'barang.jenisBarang.nama',
		],
		[
			'label'=>'Jumlah Barang',
			'value'=>'jumlahBarang',
		],
	]
])?>
<?= $uiHelper->endSingleRowBlock();?>