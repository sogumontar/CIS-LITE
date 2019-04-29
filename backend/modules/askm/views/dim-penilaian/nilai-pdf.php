<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\modules\askm\assets\AskmAsset;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinBermalam */

$this->title = 'LEMBAR PENCATATAN PERILAKU MAHASISWA';

$uiHelper=\Yii::$app->uiHelper;

function tgl_indo($tanggal, $cetak_hari = false){
    $hari = array ( 1 =>    'Senin',
				'Selasa',
				'Rabu',
				'Kamis',
				'Jumat',
				'Sabtu',
				'Minggu'
			);

	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split 	  = explode('-', $tanggal);
	$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

	if ($cetak_hari) {
		$num = date('N', strtotime($tanggal));
		return $hari[$num] . ', ' . $tgl_indo;
	}
	return $tgl_indo;
}
?>
<style>
    table {
        font-family: serif;
        border-collapse: collapse;
        width: 100%;
    }

    th {
        border: 1px solid #dddddd;
        text-align: center;
        padding: 8px;
    }
</style>
<div class="dim-penilaian-view" style="font-family: serif; font-size: 12">
    <!-- <img src="<?=\Yii::$app->getRequest()->getHostInfo().'/img/logo.jpg'?>" alt="logo del" border="0" height="100" width="100"> -->
    <p style="text-align: center;"><img src="<?=\Yii::$app->getRequest()->getHostInfo().'/img/logo.jpg'?>" alt="logo del" border="0" height="100" width="100"></p>
    <h2 style="text-align: center;">LEMBAR PENCATATAN PERILAKU MAHASISWA</h2>

    <br><br>

    <table style="width: 100%">
        <tr>
            <td style="width: 30%;">Nama</td>
            <td>: <?= $model->dim->nama ?></td>
        </tr>
        <tr>
            <td>Prodi/Angkatan</td>
            <td>: <?= $model->dim->kbkId==null?null:$model->dim->kbkId->jenjangId->nama." ".$model->dim->kbkId->kbk_ind ?>/<?= $model->dim->thn_masuk ?></td>
        </tr>
        <tr>
            <td>Semester/Tahun Ajaran</td>
            <td>: <?= '5/2018' ?></td>
        </tr>
        <tr>
            <td>Nama Asrama</td>
            <td>: <?= $model->dim->dimAsrama->kamar->asrama->name ?></td>
        </tr>
    </table>

    <br><br><br>

    <table style="border-collapse: collapse;">
        <thead>
            <tr>
                <th style="width: 20px">No</th>
                <th>Tgl.</th>
                <th>Deskripsi Tindakan</th>
                <th>Kategori</th>
                <th>Poin</th>
                <th>Akumulasi Skor</th>
                <th>Bentuk Pembinaan</th>
                <th>Ket</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $i = 0;
                $akumulasi_skor = 0;
                foreach ($pelanggaran as $nilai) {
                    if ($nilai != null && $nilai != ''){
                    $i++;
                    $date = date_create($nilai->tanggal);
                    $akumulasi_skor = $nilai->poin->poin + $akumulasi_skor;
            ?>
            <tr>
                <td style="border: 1px solid #dddddd; text-align: center; padding: 8px;"><?= $i ?></td>
                <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?= date_format($date, 'j/n/Y') ?></td>
                <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?= $nilai->poin->name ?></td>
                <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?= $nilai->poin->bentuk->name ?></td>
                <td style="border: 1px solid #dddddd; text-align: center; padding: 8px;"><?= $nilai->poin->poin ?></td>
                <td style="border: 1px solid #dddddd; text-align: center; padding: 8px;"><?= $akumulasi_skor ?></td>
                <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?= $nilai->pembinaan->name ?></td>
                <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?= $nilai->desc_pelanggaran ?></td>
            </tr>
            <?php }} ?>
        </tbody>
    </table>

    <br><br>

    <table style="width: 100%; font-weight: bold">
        <tr>
            <td style="width: 30%;">Nilai Perilaku</td>
            <td>: <?= $model->nilai_huruf ?></td>
        </tr>
        <tr>
            <td>Dikeluarkan tanggal</td>
            <td>: <?= tgl_indo(date('Y-n-j'), true) ?></td>
        </tr>
    </table>

    <br><br>

    <div style="text-align: right; font-size: 12; font-weight: bold">
        <h3>Pembina Asrama,</h3>
        <br>
        <br>
        <br>
        <br>
        _______________________
    </div>

</div>
