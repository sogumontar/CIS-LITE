<?php

namespace backend\modules\invt\models;

use Yii;
use backend\modules\invt\models\PengeluaranBarang;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

use backend\modules\invt\models\Lokasi;
use backend\modules\hrdx\models\Pegawao;
/**
 * This is the model class for table "invt_pemindahan_barang".
 *
 * @property integer $pemindahan_barang_id
 * @property integer $pengeluaran_barang_id
 * @property integer $lokasi_akhir_id
 * @property string $kode_inventori
 * @property string $tanggal_pindah
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 *
 * @property InvtPengeluaranBarang $pengeluaranBarang
 */
class PemindahanBarang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
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
        return 'invt_pemindahan_barang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pengeluaran_barang_id', 'lokasi_akhir_id', 'deleted', 'lokasi_awal_id'], 'integer'],
            [['tanggal_pindah', 'deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['kode_inventori', 'kode_inventori_awal','status_transaksi'], 'string', 'max' => 50],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pemindahan_barang_id' => 'Pemindahan Barang ID',
            'pengeluaran_barang_id' => 'Pengeluaran Barang ID',
            'lokasi_akhir_id' => 'Lokasi Akhir',
            'kode_inventori' => 'Kode Inventori Akhir',
            'tanggal_pindah' => 'Tanggal Pindah',
            'lokasi_awal_id'=>'Lokasi Awal',
            'kode_inventori_awal'=>'Kode Inventori Awal',
            'status_transaksi'=>'Status Transaksi',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengeluaranBarang()
    {
        return $this->hasOne(PengeluaranBarang::className(), ['pengeluaran_barang_id' => 'pengeluaran_barang_id']);
    }

    public function getLokasiAwal(){
        return $this->hasOne(Lokasi::className(),['lokasi_id'=>'lokasi_awal_id']);
    }

    public function getLokasiAkhir(){
        return $this->hasOne(Lokasi::className(),['lokasi_id'=>'lokasi_akhir_id']);
    }

    public function jumlahPemindahan($pengeluaran_barang_id){
        return PemindahanBarang::find()
                                ->where(['deleted'=>0])
                                ->andWhere(['pengeluaran_barang_id'=>$pengeluaran_barang_id])
                                ->sum($pemindahan_barang_id);
    }

    public function getPemindahanAwal($pengeluaran_barang_id){
        return PemindahanBarang::find()
                                ->where(['deleted'=>0])
                                ->andWhere(['pengeluaran_barang_id'=>$pengeluaran_barang_id])
                                ->orderBy(['pemindahan_barang_id'=>SORT_ASC])
                                ->one();
    }
    
    public function getDetailOleh($oleh){
        //cek pegawai
        $_detailNama = "";
        $modelPegawai = Pegawai::find()
                                ->where(['user_id'=>$oleh])
                                ->andWhere(['deleted'=>0])
                                ->one();
        if($modelPegawai!=null){
            $_detailNama = $modelPegawai->nama;
        }
        return $_detailNama;
    }
}
