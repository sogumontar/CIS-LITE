<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\modules\askm\assets\AskmAsset;

/* @var $this yii\web\View */
/* @var $model backend\modules\askm\models\IzinBermalam */

$this->title = 'Surat Izin Bermalam Mahasiswa';

$uiHelper=\Yii::$app->uiHelper;

$date_berangkat = date_create($model->rencana_berangkat);
$date_kembali = date_create($model->rencana_kembali);

function tgl_indo($tanggal){
    $bulan = array(
        1 => 'Januari',
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
    $pecahkan = explode('-', $tanggal);

    return $pecahkan[2]. ' ' . $bulan[(int)$pecahkan[1]]. ' ' . $pecahkan[0];
}
?>
    <div class="izin-bermalam-view" style="font-family: sans-serif; font-size: 11">

        <table style="width: 100%">
            <tr>
                <td style="text-align: left; width: 60%;vertical-align: top;">
                    <h2 style="text-decoration: underline; font-family: sans-serif; font-size: 18 ">&nbsp;<?= $this->title ?>&nbsp;</h2>
                </td>
                <td style="width: 3%">
                    
                </td>
                <td>
                    <img src="<?=\Yii::$app->getRequest()->getHostInfo().'/img/logo.jpg'?>" alt="logo del" border="0" height="100" width="100">
                </td>
                <td>
                    <p style="text-align: left; font-size: 10px;font-family: sans-serif;">
                        Institut Teknologi Del<br>
                        Jl. Sisingamangaraja<br>
                        Desa Sitoluama-Kec. Laguboti<br>
                        Kab. Tobasa, Sumatera Utara, Indonesia<br>
                        Telp : (0632) 331234<br>
                        Fax : 331116<br>
                        Website : www.del.ac.id<br>
                    </p>
                </td>
            </tr>
        </table>

        <br>

        <div style="font-family: sans-serif; font-size: 11">
            <p>
            DIBERIKAN IZIN BERMALAM KEPADA:
            <br>
            Nama : <?php echo $model->dim['nama']; ?>
            <br>
            <br>
            <b>Rencana IB</b>
            <br>
            Tanggal Berangkat : <?php echo tgl_indo(date_format($date_berangkat, "Y-m-d")); ?> &emsp; Pukul : <?php echo date_format($date_berangkat, "H:i:s"); ?>
            <br>
            Keperluan : <?php echo $model->desc; ?>
            <br>
            Tanggal Kembali : <?php echo tgl_indo(date_format($date_kembali, "Y-m-d")); ?> &emsp;&emsp;  Pukul : <?php echo date_format($date_kembali, "H:i:s"); ?>
            <br>
            </p>
        </div>

        <br>

        <table align="center" style="font-family: sans-serif; font-size: 11; width: 100%">
            <tr>
                <td style="text-align:center;">
                    <div>
                        Pemohon
                        <br>
                        <br>
                        <br>
                        <br>
                        (<?= $model->dim['nama']?>)
                    </div>
                </td>
                <td style="text-align:center;">
                    <div>
                        Menyetujui,petugas
                        <br>
                        <br>
                        <br>
                        <br>
                        (<?= $model->pegawai['nama']?>)
                    </div>
                </td>
                <td style="text-align:center;">
                    <div>
                        Diketahui,orangtua/wali
                        <br>
                        <br>
                        <br>
                        <br>
                        (...............................)
                    </div>
                </td>
            </tr>
        </table>

        <br>

        <p style="font-size: 11; color: red;">
            * Sebelum meninggalkan asrama untuk IB, mahasiswa/i dianjurkan untuk permisi kepada bapak/ibu asrama atau abang/kakak asrama.
        </p>

        <hr style="height:3px; padding-top: 0px;">

        <div style="font-family: sans-serif; font-size: 11">
            <b>Realisasi IB (diisi oleh petugas)</b>
            <br>
            Tanggal kembali : &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; &emsp; Pukul : 
            <br>
            <br>
            Petugas
            <br>
            <br>
            <br>
            <br>
            (...............................)
        </div>

    </div>