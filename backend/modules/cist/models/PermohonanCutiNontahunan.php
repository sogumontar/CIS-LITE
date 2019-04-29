<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "cist_permohonan_cuti_nontahunan".
 *
 * @property integer $permohonan_cuti_nontahunan_id
 * @property string $tgl_mulai
 * @property string $tgl_akhir
 * @property string $alasan_cuti
 * @property integer $lama_cuti
 * @property integer $kategori_id
 * @property string $pengalihan_tugas
 * @property integer $status_cuti_nontahunan_id
 * @property integer $pegawai_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 *
 * @property AtasanCutiNontahunan[] $AtasanCutiNontahunans
 * @property KategoriCutiNontahunan $kategori
 * @property Pegawai $pegawai
 * @property StatusCutiNontahunan $statusCutiNontahunan
 * @property StatusCutiNontahunan[] $StatusCutiNontahunans
 */
class PermohonanCutiNontahunan extends \yii\db\ActiveRecord
{
    public $atasan;
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
        return 'cist_permohonan_cuti_nontahunan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tgl_mulai', 'tgl_akhir', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['alasan_cuti', 'pengalihan_tugas'], 'string'],
            [['lama_cuti', 'kategori_id', 'status_cuti_nontahunan_id', 'pegawai_id', 'deleted'], 'integer'],
            [['kategori_id', 'pegawai_id', 'tgl_mulai', 'tgl_akhir'], 'required'],
            [['atasan'], 'each', 'rule' => ['integer']],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['kategori_id'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriCutiNontahunan::className(), 'targetAttribute' => ['kategori_id' => 'kategori_cuti_nontahunan_id']],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
            [['status_cuti_nontahunan_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusCutiNontahunan::className(), 'targetAttribute' => ['status_cuti_nontahunan_id' => 'status_cuti_nontahunan_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'permohonan_cuti_nontahunan_id' => 'Permohonan Cuti Nontahunan ID',
            'tgl_mulai' => 'Tgl Mulai',
            'tgl_akhir' => 'Tgl Masuk',
            'alasan_cuti' => 'Alasan Cuti',
            'lama_cuti' => 'Lama Cuti',
            'atasan' => 'Atasan',
            'kategori_id' => 'Jenis Cuti',
            'pengalihan_tugas' => 'Pengalihan Tugas',
            'status_cuti_nontahunan_id' => 'Status Cuti Nontahunan',
            'pegawai_id' => 'Pemohon',
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
    public function getAtasanCutiNontahunans()
    {
        return $this->hasMany(AtasanCutiNontahunan::className(), ['permohonan_cuti_nontahunan_id' => 'permohonan_cuti_nontahunan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKategori()
    {
        return $this->hasOne(KategoriCutiNontahunan::className(), ['kategori_cuti_nontahunan_id' => 'kategori_id']);
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
    public function getStatusCutiNontahunan()
    {
        return $this->hasOne(StatusCutiNontahunan::className(), ['status_cuti_nontahunan_id' => 'status_cuti_nontahunan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusCutiNontahunans()
    {
        return $this->hasMany(StatusCutiNontahunan::className(), ['permohonan_cuti_nontahunan_id' => 'permohonan_cuti_nontahunan_id']);
    }
}
