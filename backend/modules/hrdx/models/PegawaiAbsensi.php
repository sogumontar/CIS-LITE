<?php

namespace backend\modules\hrdx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "hrdx_pegawai_absensi".
 *
 * @property integer $pegawai_absensi_id
 * @property integer $pegawai_id
 * @property integer $jenis_absen_id
 * @property string $alasan
 * @property string $pengalihan_tugas
 * @property integer $jumlah_hari
 * @property integer $approval_1
 * @property integer $approval_2
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 */
class PegawaiAbsensi extends \yii\db\ActiveRecord
{

    //Alias
    public $jumlah_absen;


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
        return 'hrdx_pegawai_absensi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pegawai_id', 'jenis_absen_id', 'alasan', 'pengalihan_tugas', 'jumlah_hari', 'dari_tanggal', 'sampai_tanggal'], 'required'],
            [['pegawai_id', 'jenis_absen_id', 'jumlah_hari', 'approval_1', 'approval_2', 'deleted'], 'integer'],
            [['alasan', 'pengalihan_tugas'], 'string'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pegawai_absensi_id' => 'Pegawai Absensi ID',
            'pegawai_id' => 'Nama',
            'jenis_absen_id' => 'Jenis Izin/Cuti ',
            'alasan' => 'Alasan',
            'pengalihan_tugas' => 'Pengalihan Tugas',
            'jumlah_hari' => 'Jumlah Hari',
            'dari_tanggal' => 'Dari Tanggal',
            'sampai_tanggal' => 'Sampai Tanggal',
            'approval_1' => 'Atasan',
            'approval_2' => 'HRD',
            'status_approval_1' => 'Izin Atasan',
            'status_approval_2' => 'Izin HRD',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(),["pegawai_id" => "pegawai_id"]);
    }

    public function getApproval1()
    {
        return $this->hasOne(Pegawai::className(),["pegawai_id" => "approval_1"]);
    }

    public function getApproval2()
    {
        return $this->hasOne(Pegawai::className(),["pegawai_id" => "approval_2"]);
    }

    public function getJenisAbsen()
    {
        return $this->hasOne(JenisAbsen::className(),["jenis_absen_id" => "jenis_absen_id"]);
    }
}
