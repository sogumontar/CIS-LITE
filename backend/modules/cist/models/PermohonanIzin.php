<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "cist_permohonan_izin".
 *
 * @property integer $permohonan_izin_id
 * @property string $waktu_pelaksanaan
 * @property string $alasan_izin
 * @property string $pengalihan_tugas
 * @property integer $kategori_id
 * @property integer $lama_izin
 * @property string $file_surat
 * @property integer $status_izin_id
 * @property integer $atasan_id
 * @property integer $pegawai_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 *
 * @property AtasanIzin[] $AtasanIzins
 * @property Pegawai $pegawai
 * @property KategoriIzin $kategori
 * @property StatusIzin $statusIzin
 */
class PermohonanIzin extends \yii\db\ActiveRecord
{
    public $atasan_id;
    public $file;
    /**
     * behaviour to add created_at and updatet_at field with current datetime (timestamp)
     * and created_by and updated_by field with current user id (blameable)
     */
    public function behaviors(){
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
            ],
            'delete' => [
                'class' => DeleteBehavior::className(),
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cist_permohonan_izin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alasan_izin', 'waktu_pelaksanaan', 'lama_izin', 'pegawai_id'], 'required'],
            [['kategori_id', 'lama_izin', 'pegawai_id', 'deleted'], 'integer'],
            [['kode_file_surat', 'file_surat', 'deleted_at', 'created_at', 'updated_at', 'atasan_id'], 'safe'],
            [['waktu_pelaksanaan'], 'string', 'max' => 500],
            [['alasan_izin', 'pengalihan_tugas'], 'string'],
            //[['file'],'file'],
            [['file_surat'], 'string', 'max' => 200],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
            [['kategori_id'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriIzin::className(), 'targetAttribute' => ['kategori_id' => 'kategori_izin_id']],
            [['status_izin_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusIzin::className(), 'targetAttribute' => ['status_izin_id' => 'status_izin_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'permohonan_izin_id' => 'Permohonan Izin ID',
            'waktu_pelaksanaan' => 'Tanggal Pelaksanaan',
            'alasan_izin' => 'Alasan Izin',
            'pengalihan_tugas' => 'Pengalihan Tugas',
            'kategori_id' => 'Kategori Izin',
            'lama_izin' => 'Lama Izin',
            'file_surat' => 'Lampiran',
            'status_izin_id' => 'Status Izin ID',
            'atasan_id' => 'Atasan',
            'pegawai_id' => 'Pegawai',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAtasanIzin()
    {
        return $this->hasMany(AtasanIzin::className(), ['permohonan_izin_id' => 'permohonan_izin_id']);
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
    public function getKategori()
    {
        return $this->hasOne(KategoriIzin::className(), ['kategori_izin_id' => 'kategori_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusIzin()
    {
        return $this->hasOne(StatusIzin::className(), ['status_izin_id' => 'status_izin_id']);
    }
}
