<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "cist_permohonan_cuti_tahunan".
 *
 * @property integer $permohonan_cuti_tahunan_id
 * @property string $waktu_pelaksanaan
 * @property string $alasan_cuti
 * @property integer $lama_cuti
 * @property string $pengalihan_tugas
 * @property integer $status_izin_id
 * @property integer $pegawai_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 *
 * @property AtasanCutiTahunan[] $AtasanCutiTahunans
 * @property Pegawai $pegawai
 * @property StatusCutiTahunan $statusIzin
 * @property StatusCutiTahunan[] $StatusCutiTahunans
 * @property WaktuCutiTahunan[] $WaktuCutiTahunans
 */
class PermohonanCutiTahunan extends \yii\db\ActiveRecord
{
    public $atasan;
    public $sisa_kuota;
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
        return 'cist_permohonan_cuti_tahunan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alasan_cuti', 'waktu_pelaksanaan', 'pegawai_id'], 'required'],
            [['alasan_cuti', 'pengalihan_tugas'], 'string'],
            [['atasan'], 'each', 'rule' => ['integer']],
            [['lama_cuti', 'status_izin_id', 'pegawai_id', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['waktu_pelaksanaan'], 'string', 'max' => 500],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
            [['status_izin_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusCutiTahunan::className(), 'targetAttribute' => ['status_izin_id' => 'status_cuti_tahunan_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'permohonan_cuti_tahunan_id' => 'Permohonan Cuti Tahunan ID',
            'waktu_pelaksanaan' => 'Waktu Pelaksanaan',
            'alasan_cuti' => 'Alasan Cuti',
            'atasan' => 'Atasan',
            'lama_cuti' => 'Lama Cuti',
            'pengalihan_tugas' => 'Pengalihan Tugas',
            'status_izin_id' => 'Status Izin ID',
            'pegawai_id' => 'Pegawai ID',
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
    public function getAtasanCutiTahunans()
    {
        return $this->hasMany(AtasanCutiTahunan::className(), ['permohonan_cuti_tahunan_id' => 'permohonan_cuti_tahunan_id']);
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
    public function getStatusIzin()
    {
        return $this->hasOne(StatusCutiTahunan::className(), ['status_cuti_tahunan_id' => 'status_izin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusCutiTahunan()
    {
        return $this->hasOne(StatusCutiTahunan::className(), ['permohonan_cuti_tahunan_id' => 'permohonan_cuti_tahunan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWaktuCutiTahunans()
    {
        return $this->hasMany(WaktuCutiTahunan::className(), ['permohonan_cuti_tahunan_id' => 'permohonan_cuti_tahunan_id']);
    }
}
