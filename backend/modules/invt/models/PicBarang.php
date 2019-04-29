<?php

namespace backend\modules\invt\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

use backend\modules\hrdx\models\Pegawai;
/**
 * This is the model class for table "invt_pic_barang".
 *
 * @property integer $pic_barang_id
 * @property integer $pengeluaran_barang_id
 * @property integer $pegawai_id
 * @property string $tgl_assign
 * @property string $keterangan
 * @property integer $is_unassign
 * @property string $tgl_unassign
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 *
 * @property InvtPengeluaranBarang $pengeluaranBarang
 * @property HrdxPegawai $pegawai
 * @property InvtPicBarangFile[] $invtPicBarangFiles
 */
class PicBarang extends \yii\db\ActiveRecord
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
        return 'invt_pic_barang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pengeluaran_barang_id', 'pegawai_id', 'is_unassign', 'deleted','unit_id'], 'integer'],
            [['tgl_assign', 'tgl_unassign', 'deleted_at', 'updated_at', 'created_at','pegawai_id'], 'safe'],
            [['keterangan'], 'string'],
            [['tgl_assign','pegawai_id'],'required'],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pic_barang_id' => 'Pic Barang ID',
            'pengeluaran_barang_id' => 'Pengeluaran Barang ID',
            'pegawai_id' => 'Nama PIC',
            'tgl_assign' => 'Tanggal Assign',
            'keterangan' => 'Keterangan',
            'is_unassign' => 'Is Unassign',
            'tgl_unassign' => 'Tanggal Unassign',
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
    public function getPengeluaranBarang()
    {
        return $this->hasOne(PengeluaranBarang::className(), ['pengeluaran_barang_id' => 'pengeluaran_barang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(), ['pegawai_id' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPicBarangFile()
    {
        return $this->hasMany(PicBarangFile::className(), ['pic_barang_id' => 'pic_barang_id']);
    }

    public function getUnit(){
        return $this->hasOne(Unit::className(),['unit_id'=>'unit_id']);
    }
}
