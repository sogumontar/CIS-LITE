<?php

use yii\helpers\Html;
use backend\modules\inst\models\InstApiModel;

?>
<!doctype html>
<html>
    <head>
        <style>
        table, th, td{
            border-collapse: collapse;
        }
    </style>
        <title>Surat Tugas <?= $model->no_surat ?></title>
    </head>
<?php 
    $id = $_GET['id'];
	list($tanggalBerangkat, $pukulBerangkat) = explode(' ', $model->tanggal_berangkat);
	list($tanggalKembali, $pukulKembali) = explode(' ', $model->tanggal_kembali);
    $getMonth = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');        

        $month = $getMonth[date('n', strtotime($model->tanggal_surat))-1];
        $monthBerangkat = $getMonth[date('n', strtotime($model->tanggal_berangkat))-1];
        $monthKembali = $getMonth[date('n', strtotime($model->tanggal_kembali))-1];
        $monthMulai = $getMonth[date('n', strtotime($model->tanggal_mulai))-1];
        $monthSelesai = $getMonth[date('n', strtotime($model->tanggal_selesai))-1];
        $monthKembaliKerja = $getMonth[date('n', strtotime($model->kembali_bekerja))-1];
        $tgl_surat = '';
        $tgl_berangkat = '';
        $tgl_kembali = '';
        $tgl_mulai = '';
        $tgl_selesai = '';
        $tgl_kembaliKerja = '';
        $tgl_surat .=  date('d', strtotime($model->tanggal_surat)) .' '.$month .' '.date('Y', strtotime($model->tanggal_surat));
        $tgl_berangkat .=  date('d', strtotime($model->tanggal_berangkat)) .' '.$monthBerangkat .' '.date('Y', strtotime($model->tanggal_berangkat));
        $tgl_kembali .=  date('d', strtotime($model->tanggal_kembali)) .' '.$monthKembali .' '.date('Y', strtotime($model->tanggal_kembali));
        $tgl_mulai .=  date('d', strtotime($model->tanggal_mulai)) .' '.$monthMulai .' '.date('Y', strtotime($model->tanggal_mulai));
        $tgl_selesai .=  date('d', strtotime($model->tanggal_selesai)) .' '.$monthSelesai .' '.date('Y', strtotime($model->tanggal_selesai));
        $tgl_kembaliKerja .=  date('d', strtotime($model->kembali_bekerja)) .' '.$monthKembaliKerja .' '.date('Y', strtotime($model->kembali_bekerja));
?>
    <body>
        <table 	style ="width:100%">
            <tr>
                <th>
                    <img src="<?=Yii::$app->getRequest()->getHostInfo().'/img/logo.jpg'?>" style="width:90px;">
				</th>
                <td style="text-align: center">
                	<div >
                		<h3>INSTITUT TEKNOLOGI DEL</h3>
                		<p>JL. Sisingamangaraja, Laguboti 22381<br>
                		Toba Samosir, Sumatera Utara, Laguboti, 22381<br>
                		Telp.: (0632) 331234, Fax.: (0632) 331116<br>
                		<u>info@del.ac.id, www.del.ac.id</u>
                		</p>
                	</div>
                </td>
            </tr>
        </table>
        <hr>
        <p style="text-align: center;">
        	<b>Surat Tugas</b><br>
        	No: <?= $model->no_surat ?>
        </p>
        <p style="text-align: justify">
            Wakil Rektor Bidang Perencanaan, Keuangan, dan Sumber Daya Institut Teknologi Del (IT Del) dengan ini memberikan tugas kepada:
        </p>
        <table border="1" style="width:100%;position: center">
            <tr >
            	<th style="text-align: left;">No.</th>
            	<th style="text-align: left;">Nama</th>
            	<th style="text-align: left;">Jabatan</th>
            	<th style="text-align: left;">NIP/NIDN</th>
            </tr>
			<?php
				$idx = 1;
                $inst = new InstApiModel();
				foreach($pesertas as $peserta){
                    $jabatan = $inst->getJabatanByPegawaiId($peserta->pegawai_id);
                    $posisi = '';
                    $first = true;
                    foreach ($jabatan as $j) {
                        if(!$first)
                            $posisi .= ', ';
                        $posisi .= $j->jabatan;
                        if($first)
                            $first = false;
                    }
					echo ("	<tr style='text-align: left;'>
								<td>". $idx .".</td>
								<td>" . $peserta->nama . "</td>
								<td>". $posisi ."</td>
								<td>" . $peserta->nip . "</td>
							</tr>");
					$idx++;
				}
			?>
            
        </table>

        <p style="text-align: justify;">Untuk mengikuti pelaksanaan <?= $model->nama_kegiatan ?>, pada:</p>
        <table style="margin-left: 60px;">
        	<tr>
        		<td>Tanggal</td>
        		<td>:</td>
        		<td>
                    <?php if(date('d', strtotime($model->tanggal_mulai)) == date('d', strtotime($model->tanggal_selesai))){?>
                        <?= $tgl_mulai?>
                    <?php } 
                    else{ ?>
                        <?= $tgl_mulai . " - " . $tgl_selesai ?>
                    <?php } ?>
                    </td>
        	</tr>
        	<tr>
        		<td>Pukul</td>
        		<td>:</td>
        		<td><?= date('H:i', strtotime($model->tanggal_mulai)) . " WIB - selesai" ?></td>
        	</tr>
        	<tr>
        		<td>Alamat</td>
        		<td>:</td>
        		<td><?= $model->tempat ?></td>
        	</tr>
        	<tr>
        		<td>Agenda</td>
        		<td>:</td>
        		<td><?= $model->agenda ?></td>
        	</tr>
        </table>

        <p style="text-align: justify;">Untuk persiapan dan pelaksanaan, nama yang tersebut dalam surat tugas berangkat dari IT Del ke <?= $model->tempat ?>, pada tanggal <?= $tgl_berangkat ?> pukul <?= date('H:i', strtotime($model->tanggal_berangkat)) ?> WIB. Kembali ke IT Del pada tanggal <?= $tgl_kembali ?> pukul <?= date('H:i', strtotime($model->tanggal_kembali)) ?> WIB.
        <br><br>
        Saudara/i yang tersebut dalam surat tugas, diminta melakukan pengalihan tugas kepada rekan kerja selama dinas luar, dan kembali aktif bekerja pada tanggal <?= $tgl_kembaliKerja ?>. Dan melaporkan hasil pelaksanaan tugas, paling lambat 2 (dua) hari setelah penugasan.
        <br><br>
        Berkaitan dengan penugasan ini, biaya perjalanan dinas yang ditanggung adalah :
        </p>

        <table border="1" style="width:100%">
			<?php
				$datediff = strtotime($model->tanggal_kembali) - strtotime($model->tanggal_berangkat);
				$penginapan = round($datediff / (60 * 60 * 24)) - 1;
				$allowance = $penginapan + 1;
			?>
			<tr>
        		<td>1.</td>
        		<td>Transportasi</td>
				<td><?= $model->transportasi ?></td>
        	</tr>
			<tr>
				
        		<td>2.</td>
        		<td>Penginapan</td>
				<td><?= $penginapan ?> hari</td>
        	</tr>
        	<tr>
        		<td>3.</td>
        		<td>Allowance</td>
        		<td><?= $allowance ?> hari</td>
        	</tr>
        </table>

        <?php if($model->catatan != NULL){?>
            <p>Catatan : <?= $model->catatan ?></p>
        <?php } 
        else{?>
            <br>
        <?php } ?>

		<?php $today = time(); ?>
		Sitoluama, <?= $tgl_surat ?>
		<br>
        <?=$nama_jabatan?><br><br><br><br><br><br>
        <?=$hasil_jabatan?>
</body>
</html>