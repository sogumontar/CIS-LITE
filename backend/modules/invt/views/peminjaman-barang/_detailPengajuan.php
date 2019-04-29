<?php
use yii\helpers\Html;
$uiHelper = Yii::$app->uiHelper;
?>
<div class="detail-pengajuan">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th class="col-md-2">Unit Inventori</th>
				<td><?= $modelPengajuan->unit->nama?></td>
			</tr>
			<tr>
			<th class="col-md-2">Tanggal Pinjam</th>
				<td><?= $modelPengajuan->tgl_pinjam?></td>
			</tr>
			<tr>
			<th class="col-md-2">Tanggal Kembali</th>
				<td><?= $modelPengajuan->tgl_kembali?></td>
			</tr>
			<tr>
			<th class="col-md-2">Alasan/Deskripsi</th>
				<td><?= $modelPengajuan->deskripsi?></td>
			</tr>
		</tbody>
	</table>
</div>