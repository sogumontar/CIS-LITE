<?php

namespace backend\modules\invt\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

use backend\modules\invt\models\DetailPeminjamanBarang;
use backend\modules\invt\models\Unit;
use backend\modules\invt\models\User;
use backend\modules\dimx\models\Dim;
use backend\modules\hrdx\models\Pegawai;
/**
 * This is the model class for table "invt_peminjaman_barang".
 *
 * @property integer $peminjaman_barang_id
 * @property string $tgl_pinjam
 * @property string $tgl_kembali
 * @property integer $oleh
 * @property integer $status_approval
 * @property integer $approved_by
 * @property string $deskripsi
 * @property integer $status_kembali
 * @property string $tgl_realisasi_kembali
 * @property integer $unit_id
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 *
 * @property InvtDetailPeminjamanBarang[] $invtDetailPeminjamanBarangs
 * @property SysxUser $approvedBy
 * @property InvtRUnit $unit
 * @property SysxUser $oleh0
 */
class PeminjamanBarang extends \yii\db\ActiveRecord
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
        return 'invt_peminjaman_barang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tgl_pinjam', 'tgl_kembali', 'tgl_realisasi_kembali', 'deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['oleh', 'status_approval', 'approved_by', 'status_kembali', 'unit_id', 'deleted'], 'integer'],
            [['deskripsi'], 'string'],
            [['tgl_pinjam', 'tgl_kembali', 'deskripsi', 'unit_id'], 'required'],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'peminjaman_barang_id' => 'Peminjaman Barang ID',
            'tgl_pinjam' => 'Tanggal Pinjam',
            'tgl_kembali' => 'Tanggal Kembali',
            'oleh' => 'Oleh',
            'status_approval' => 'Status Approval',
            'approved_by' => 'Approved By',
            'deskripsi' => 'Deskripsi',
            'status_kembali' => 'Status Kembali',
            'tgl_realisasi_kembali' => 'Tgl Realisasi Kembali',
            'unit_id' => 'Unit ID',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetailBarang()
    {
        return $this->hasMany(DetailPeminjamanBarang::className(), ['peminjaman_barang_id' => 'peminjaman_barang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovedBy()
    {
        return $this->hasOne(User::className(), ['user_id' => 'approved_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['unit_id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOleh()
    {
        return $this->hasOne(User::className(), ['user_id' => 'oleh']);
    }

    //$id => user_id
    public function getDetailNama($id)
    {
        //cek DIM
        $peminjam="";
        $modelDim = Dim::find()
                            ->where(['user_id'=>$id])
                            ->andWhere(['deleted'=>0])
                            ->one();
        //cekPegawai
        $modelPegawai = Pegawai::find()
                            ->where(['user_id'=>$id])
                            ->andWhere(['deleted'=>$id])
                            ->one();
        if($modelDim!=null){
                $peminjam = $modelDim->nama;
        }elseif($modelPegawai!=null){
                $peminjam = $modelPegawai->nama;
        }
        return $peminjam;
    }
}
