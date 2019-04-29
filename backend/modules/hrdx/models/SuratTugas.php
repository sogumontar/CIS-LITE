<?php

namespace backend\modules\hrdx\models;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

use Yii;

/**
 * This is the model class for table "hrdx_surat_tugas".
 *
 * @property integer $surat_tugas_id
 * @property integer $perequest
 * @property string $tugas
 * @property string $kota_tujuan
 * @property string $lokasi_tugas
 * @property string $tanggal_berangkat
 * @property string $tanggal_kembali
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property string $keterangan
 * @property string $pemberi_tugas
 * @property string $catatan
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 */
class SuratTugas extends \yii\db\ActiveRecord
{
    //behaviour to add created_at and updatet_at field with current timestamp
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

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hrdx_surat_tugas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['no_surat_tugas', 'perequest', 'tugas', 'kota_tujuan', 'lokasi_tugas', 'tanggal_berangkat','tanggal_kembali', 'tanggal_mulai','tanggal_selesai','keterangan','pemberi_tugas'], 'required'],
            [['perequest', 'pemberi_tugas', 'status', 'deleted'], 'integer'],
            [['tanggal_berangkat', 'tanggal_kembali', 'tanggal_mulai', 'tanggal_selesai', 'deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['no_surat_tugas','keterangan'], 'string'],
            [['tugas', 'catatan'], 'string', 'max' => 255],
            [['kota_tujuan','lokasi_tugas'], 'string', 'max' => 100],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'surat_tugas_id' => 'Surat Tugas ID',
            'no_surat_tugas' => 'No. Surat Tugas',
            'perequest' => 'Perequest',
            'tugas' => 'Tugas',
            'kota_tujuan' => 'Kota Tujuan',
            'lokasi_tugas' => 'Lokasi Tugas',
            'tanggal_berangkat' => 'Tanggal Berangkat',
            'tanggal_kembali' => 'Tanggal Kembali',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'keterangan' => 'Keterangan Tugas',
            'pemberi_tugas' => 'Pemberi Tugas',
            'catatan' => 'Catatan',
            'status' => 'Status',
            'nama_file' => 'Nama File',
            'kode_file' => 'Kode File',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(), ["pegawai_id" => "perequest"]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStrukturJabatan()
    {
        return $this->hasOne(StrukturJabatan::className(), ["struktur_jabatan_id" => "pemberi_tugas"]);
    }
}
