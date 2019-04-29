<?php

namespace backend\modules\invt\models;
use Yii;
use yii\base\Model;

class BarangKeluarForm extends Model{
	public $tanggal_keluar;
	public $keterangan;

	public function rules(){
		return [
			[['tanggal_keluar','keterangan'],'required'],
		];
	}

	public function attributeLabels(){
		return [
			'tanggal_keluar'=>'Tanggal Keluar',
			'keterangan'=>'Keterangan Distribusi',
		];
	}
}
?>