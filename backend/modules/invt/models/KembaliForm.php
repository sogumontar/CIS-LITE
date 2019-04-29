<?php
namespace backend\modules\invt\models;
use Yii;
use yii\base\Model;

class KembaliForm extends Model{
	public $cek_status_barang;
	public $tgl_realisasi_kembali;

	public function rules(){
		return [
			[['cek_status_barang','tgl_realisasi_kembali'],'required'],
		];
	}

	public function attributeLabels(){
		return [
			'cek_status_barang'=>'Status Barang Kembali',
			'tgl_realisasi_kembali'=>'Tanggal Realisasi Kembali',
		];
	}
}
?>