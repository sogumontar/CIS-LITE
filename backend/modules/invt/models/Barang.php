<?php

namespace backend\modules\invt\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

use backend\modules\invt\models\JenisBarang;
use backend\modules\invt\models\Kategori;
use backend\modules\invt\models\Lokasi;
use backend\modules\invt\models\Satuan;
use backend\modules\invt\models\Unit;
use backend\modules\invt\models\Brand;
use backend\modules\invt\models\Vendor;
use backend\modules\invt\models\PengeluaranBarang;

/**
 * This is the model class for table "invt_barang".
 *
 * @property integer $barang_id
 * @property string $nama_barang
 * @property integer $jenis_barang_id
 * @property integer $kategori_id
 * @property integer $jumlah
 * @property string $supplier
 * @property string $harga
 * @property string $tanggal_masuk
 * @property integer $satuan_id
 * @property string $desc
 * @property integer $kapasitas
 * @property string $nama_file
 * @property string $kode_file
 * @property integer $jumlah_rusak
 * @property string $status_akhir
 * @property integer $unit_inventori_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 */
class Barang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    //for alias select
    public $jumlahBarang;
    public $generate_kode;
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
        return 'invt_barang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenis_barang_id', 'kategori_id',  'jumlah','satuan_id', 'unit_id','brand_id', 'vendor_id','deleted'], 'integer'],
            [['total_harga','harga_per_barang'], 'number'],
            [['tanggal_masuk', 'deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['desc'], 'string'],
            [['nama_barang', 'jenis_barang_id', 'kategori_id', 'satuan_id','tanggal_masuk'], 'required'],
            [['kapasitas'], 'string', 'max' => 50],
            [['supplier'], 'string', 'max' => 150],
            ['serial_number', 'string', 'max'=>100],
            [['nama_barang', 'nama_file', 'kode_file'], 'string', 'max' => 200],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            ['nama_file','file','extensions'=>'png, jpg, jpeg'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'barang_id' => 'Barang',
            'nama_barang' => 'Nama Barang',
            'jenis_barang_id' => 'Jenis Barang',
            'kategori_id' => 'Kategori',
            'jumlah' => 'Total Jumlah',
            'supplier' => 'Supplier',
            'total_harga' => 'Harga',
            'harga_per_barang'=>'Harga @ Barang',
            'tanggal_masuk' => 'Tanggal Masuk',
            'satuan_id' => 'Satuan',
            'desc' => 'Desc',
            'kapasitas' => 'Kapasitas',
            'nama_file' => 'Nama File',
            'kode_file' => 'Kode File',
            'jumlah_rusak' => 'Jumlah Rusak',
            'unit_id' => 'Unit Inventori',
            'serial_number'=>'Serial Number',
            'brand_id'=>'Brand',
            'vendor_id'=>'Vendor',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    public function getJenisBarang()
    {
        return $this->hasOne(JenisBarang::className(), ['jenis_barang_id'=>'jenis_barang_id']);
    }
    public function getKategori()
    {
        return $this->hasOne(Kategori::className(), ['kategori_id'=>'kategori_id']);
    }
    public function getSatuan()
    {
        return $this->hasOne(Satuan::className(), ['satuan_id'=>'satuan_id']);
    }

    public function getUnit()
    {
        return $this->hasOne(Unit::className(),['unit_id'=>'unit_id']);
    } 

    public function getBrand(){
        return $this->hasOne(Brand::className(),['brand_id'=>'brand_id']);
    }

    public function getVendor(){
        return $this->hasOne(Vendor::className(),['vendor_id'=>'vendor_id']);
    }

    public function getSummaryJumlah()
    {
        return $this->hasOne(SummaryJumlah::className(),['barang_id'=>'barang_id']);
    }

    public function getPengeluaranBarang(){
        return $this->hasMany(PengeluaranBarang::className(),['barang_id'=>'barang_id'])->andWhere(['deleted'=>0]);
    }

    public function getPengeluaranBarangbyId($barang_id){
        return PengeluaranBarang::find()
                                    ->where(['barang_id'=>$barang_id])
                                    ->andWhere(['deleted'=>0])
                                    ->orderBy(['tgl_keluar'=>SORT_DESC]);
    }

    public function getLokasiDistribusi($barang_id){
        return PengeluaranBarang::find()
                                    ->select(['lokasi_id','jumlahBarang'=>'SUM(jumlah)'])
                                    ->where(['barang_id'=>$barang_id])
                                    ->andWhere(['deleted'=>0])
                                    ->groupBy(['lokasi_id'])
                                    ->all();
    }

    public function countBarangKeluar($barang_id)
    {
        return PengeluaranBarang::find()
                                    ->where(['barang_id'=>$barang_id])
                                    ->andWhere(['deleted'=>0])
                                    ->count();
    }
}