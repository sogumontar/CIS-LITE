<?php
use backend\modules\hrdx\assets\HrdxAsset;
$uiHelper = \Yii::$app->uiHelper;
HrdxAsset::register($this);
?>

<style type="text/css">
	.wrapper{
		font-family: "Times New Roman", Times, serif;
		font-size: 14px;
		text-align: justify;
		text-justify: inter-word;
	}

	.icon{
		display: block;
		margin-left: auto;
		margin-right: auto;
		width: 50px;
		margin-bottom: 10px;
	}

	.judul{
		text-align: center;
		margin-bottom: 30px;
	}

	.table-modify{
	    width: 100%;
	    border-spacing: 0px;
	    border-collapse: collapse;
	}

	.table-modify th{
	    border: 1px solid #b8b8b8;
	    text-align: center;
	    padding-top: 5px;
	    padding-bottom: 5px;
	}

	.table-modify td{
	    border: 1px solid #b8b8b8;
	    padding-top: 3px;
	    padding-bottom: 3px;
	    padding-left: 5px;
	}
</style>

<?= $uiHelper->beginContentRow() ?>
    <?= $uiHelper->beginContentBlock(['id' => 'basic-grid','width' => 12,]) ?>

    <?php
		$hari=['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
		$bulan=['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
	?>

	<div class="icon">
	    <img src="<?=Yii::$app->getRequest()->getHostInfo().'/img/logo.jpg'?>">
	</div>

	<div class="wrapper">

	    <div class="judul">
	        <font style="font-weight:bold;">Surat Tugas</font>
	        <br>
	        <font>No. <?=$model->no_surat_tugas;?></font>
	    </div>

	    <div class="isi">
	    	<?=$model->strukturJabatan->deskripsi;?> Institut Teknologi  Del (IT  Del) dengan ini memberi tugas kepada:<br><br>
	    	<table class="table-modify">
		    <thead>
		      <tr>
		        <th style="width:30px;">No</th>
		        <th>Nama</th>
		        <th>Jabatan</th>
		        <th>NIK</th>
		      </tr>
		    </thead>
		    <tbody>
		    <?php
		    	foreach ($penerima_tugas as $key => $value) {
		    		echo '<tr>';
		    		echo '<td style="padding:0;text-align:center;">'.($key+1).'</td>';
		    		echo '<td>'.$value->pegawai->nama.'</td>';
		    		echo '<td>'.$value->pegawai->posisi.'</td>';
		    		echo '<td>'.$value->pegawai->nip.'</td>';
		    		echo '</tr>';
		    	}
		    ?>
		    </tbody>
		  	</table>

		  	<br>

		  	Untuk melaksanakan tugas yang diadakan pada:

			<table>
		    <tbody>
			    <tr>
			        <th>Hari/ Tanggal</th>
			        <td style="width:30px; text-align:center;height:30px;">:</td>
			        <td><?=$hari[date('N', strtotime($model->tanggal_mulai))-1]?>/ <?=date('j', strtotime($model->tanggal_mulai))?> 
			        	<?=$bulan[date('n', strtotime($model->tanggal_mulai))-1]?> 
						<?=date('o', strtotime($model->tanggal_mulai))?> </td>
			    </tr>
				<tr>
			        <th>Pukul</th>
			        <td style="width:30px; text-align:center;height:30px;">:</td>
			        <td><?=date('G:i', strtotime($model->tanggal_mulai));?> </td>
				</tr>
				<tr>
			        <th>Tempat</th>
			        <td style="width:30px; text-align:center;height:30px;">:</td>
			        <td><?=$model->lokasi_tugas?>, <?=$model->kota_tujuan?></td>
				</tr>
				<tr>
			        <th>Perihal</th>
			        <td style="width:30px; text-align:center;height:30px;">:</td>
			        <td><?=$model->keterangan?></td>
		      	</tr>
		    </tbody>
		  	</table>
			
			<br>

			Untuk persiapan dan pelaksanaan, penerima tugas 

			<?php
				// $result='';
				// foreach ($penerima_tugas as $key => $value) {
				// 	if($key>0){
				// 		$result=$result.', ';
				// 	}
				// 	$result=$result.$value->pegawai->nama;
				// }

				// echo '('.$result.')';
			?>

			berangkat dari IT Del - <?=$model->kota_tujuan?> pada <?=$hari[date('N', strtotime($model->tanggal_berangkat))-1]?>, 
			<?=date('j', strtotime($model->tanggal_berangkat))?> <?=$bulan[date('n', strtotime($model->tanggal_berangkat))-1]?> 
			<?=date('o', strtotime($model->tanggal_berangkat))?> pukul <?=date('G:i', strtotime($model->tanggal_berangkat));?> 

			dan akan kembali pada <?=$hari[date('N', strtotime($model->tanggal_kembali))-1]?>, 
			<?=date('j', strtotime($model->tanggal_kembali))?> <?=$bulan[date('n', strtotime($model->tanggal_kembali))-1]?> 
			<?=date('o', strtotime($model->tanggal_kembali))?> pukul <?=date('G:i', strtotime($model->tanggal_kembali));?> 

			setelah menyelesaikan tugas

			<?php
				if(is_null($fasilitas_transportasi)){
					echo '.';
				}else{
					echo 'dengan menggunakan '.$fasilitas_transportasi->keterangan.'.';
				}
				
			?>

			<br>
			<br>

			Berkaitan dengan surat tugas ini, biaya perjalanan dinas yang ditanggung adalah:
			<br>
			<br>
			<table class="table-modify">
		    <thead>
		      <tr>
		        <th style="width:30px;">No</th>
		        <th>Nama Fasilitas</th>
		        <th>Keterangan</th>
		      </tr>
		    </thead>
		    <tbody>
		    <?php
		    	foreach ($fasilitas as $key => $value) {
		    		echo '<tr>';
			        echo '<td style="text-align:center;">'.($key+1).'</td>';
			        echo '<td>'.$value->jenisFasilitas->nama.'</td>';
			        echo '<td>'.$value->keterangan.'</td>';
			        echo '</tr>';
		    	}
		    ?>
		    </tbody>
		  	</table>

		  	<?php
		  		if (!is_null($model->catatan) && $model->catatan!='') {
		  			echo '<br>';
		  			echo '<br>';
		  			echo 'Catatan: '.$model->catatan;
		  		}
		  	?>

		  	<br>
			<br>
			Kepada penerima tugas diminta membuat laporan tertulis setelah pelaksanaan tugas ini, paling lambat seminggu setelah penugasan.

			<br>
			<br>

			Sitoluama, <?=date('j').' '.$bulan[date('n')-1].' '.date('o')?>
			<br>
			<?=$pemberi_tugas->strukturJabatan->deskripsi?>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<?=$pemberi_tugas->pegawai->nama?>
	    </div>
		
	</div>

    <?= $uiHelper->endContentBlock() ?>
<?= $uiHelper->endContentRow() ?>