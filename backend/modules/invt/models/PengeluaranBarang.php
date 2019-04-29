<?php

namespace backend\modules\invt\models;

use Yii;
use backend\modules\invt\models\Barang;
use backend\modules\invt\models\Lokasi;
use backend\modules\invt\models\Unit;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;
/**
 * This is the model class for table "invt_pengeluaran_barang".
 *
 * @property integer $pengeluaran_barang_id
 * @property integer $barang_id
 * @property integer $lokasi_id
 * @property string $kode_inventori
 * @property integer $jumlah
 * @property string $tanggal_keluar
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 */
class PengeluaranBarang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    //for form
    public $jumlah_gudang;
    public $jumlah_rusak;
    public $unit;
    public $total_jumlah;

    //alias for select statement
    public $jumlahBarang;
    public $jum_barang_lokasi;
    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
            [
                'class' => BlameableBehavior::className(),
            ],
            [
                'class' => DeleteBehavior::className(),
                'hardDeleteTaskName' => 'HardDeleteDB', //default
                'enableSoftDelete' => true, //default, set false jika behavior ini ingin di bypass. cth, untuk proses debugging
            ]
        ];
    }

    public static function tableName()
    {
        return 'invt_pengeluaran_barang';
    }

    /**
     * @inheritdoc
     */
    public function rules() 
    { 
        return [
            [['barang_id', 'unit_id', 'lokasi_id', 'deleted','jumlah','is_has_pic'], 'integer'],
            [['tgl_keluar', 'deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['status_akhir'], 'string', 'max'=>50],
            [['kode_inventori'], 'string', 'max' => 120],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ]; 
    } 


    /**
     * @inheritdoc
     */
    public function attributeLabels() 
    { 
        return [ 
            'pengeluaran_barang_id' => 'Pengeluaran Barang ID',
            'barang_id' => 'Barang ID',
            'jumlah'=>'Jumlah',
            'kode_inventori' => 'Kode Inventori',
            'unit_id' => 'Unit ID',
            'lokasi_id' => 'Lokasi ID',
            'tgl_keluar' => 'Tgl Keluar',
            'status_akhir' => 'Status Akhir',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ]; 
    } 

    // validasi jumlah pengeluaran
    // public function validateJumlahKeluar($attribute, $params)
    // {
    //     if($this->isTrueJumlah($this->jumlah)){
    //         $this->addError('jumlah', 'Jumlah pengeluaran harus lebih kecil atau sama dengan jumlah gudang');
    //     }
    // }

    // public function isTrueJumlah($_jumlah)
    // {
    //     if($_jumlah > $this->barang->jumlah_gudang){
    //         return true;
    //     }
    //     return false;
    // }

    public function getBarang()
    {
        return $this->hasOne(Barang::className(),['barang_id'=>'barang_id']);
    }
    public function getLokasi()
    {
        return $this->hasOne(Lokasi::className(),['lokasi_id'=>'lokasi_id']);
    }

    public function getDetailUnit()
    {
        return $this->hasOne(Unit::className(), ['unit_id'=>'unit_id']);
    }

    public function getKeteranganPengeluaran(){
        return $this->hasOne(KeteranganPengeluaran::className(),['keterangan_pengeluaran_id'=>'keterangan_pengeluaran_id']);
    }

    public function getHistoriPemindahan(){
        return $this->hasMany(PemindahanBarang::className(),['pengeluaran_barang_id'=>'pengeluaran_barang_id']);
    }

    //get jumlah barang yang disistribusikan
    public function getJumlahBarang($lokasi_id,$barang_id,$tanggal_keluar)
    {
        return PengeluaranBarang::find()
                                    ->where(['lokasi_id'=>$lokasi_id])
                                    ->andWhere(['barang_id'=>$barang_id])
                                    ->andWhere(['tgl_keluar'=>$tanggal_keluar])
                                    ->andWhere(['deleted'=>0])
                                    ->sum('jumlah');
    }
}
