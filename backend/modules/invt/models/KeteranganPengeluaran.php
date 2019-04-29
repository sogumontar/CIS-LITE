<?php

namespace backend\modules\invt\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

use backend\modules\hrdx\models\Pegawai;
/**
 * This is the model class for table "invt_keterangan_pengeluaran".
 *
 * @property integer $keterangan_pengeluaran_id
 * @property string $tgl_keluar
 * @property integer $unit_id
 * @property string $keterangan
 * @property integer $total_barang_keluar
 * @property integer $oleh
 * @property integer $lokasi_distribusi
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $updated_at
 *
 * @property InvtPengeluaranBarang[] $invtPengeluaranBarangs
 */
class KeteranganPengeluaran extends \yii\db\ActiveRecord
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
        return 'invt_keterangan_pengeluaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tgl_keluar','keterangan'], 'required'],
            [['tgl_keluar', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['unit_id', 'total_barang_keluar', 'oleh', 'lokasi_distribusi', 'deleted'], 'integer'],
            [['keterangan'], 'string'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'keterangan_pengeluaran_id' => 'Keterangan Pengeluaran ID',
            'tgl_keluar' => 'Tgl Keluar',
            'unit_id' => 'Unit ID',
            'keterangan' => 'Keterangan',
            'total_barang_keluar' => 'Total Barang Keluar',
            'oleh' => 'Oleh',
            'lokasi_distribusi' => 'Lokasi Distribusi',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPengeluaranBarang()
    {
        return $this->hasMany(PengeluaranBarang::className(), ['keterangan_pengeluaran_id' => 'keterangan_pengeluaran_id']);
    }

    public function getLokasi()
    {
        return $this->hasOne(Lokasi::className(),['lokasi_id'=>'lokasi_distribusi']);
    }

    public function getDetailUnit()
    {
        return $this->hasOne(Unit::className(), ['unit_id'=>'unit_id']);
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
