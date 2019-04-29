<?php

namespace backend\modules\invt\models;
use Yii;
use yii\base\Model;

class PindahBarangForm extends Model{
	public $lokasi_awal;
	public $lokasi_akhir;
	public $tgl_pindah;

	public function rules(){
		return [
			[['lokasi_akhir', 'lokasi_awal', 'tgl_pindah'],'required'],
		];
	}

	public function attributeLabels(){
		return [
			'lokasi_awal'=>'Lokasi Awal',
			'lokasi_akhir'=>'Lokasi Tujuan',
			'tgl_pindah'=>'Tanggal Pindah',
		];
	}
}
?>