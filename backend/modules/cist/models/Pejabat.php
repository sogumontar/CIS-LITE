<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "inst_pejabat".
 *
 * @property integer $pejabat_id
 * @property integer $pegawai_id
 * @property integer $struktur_jabatan_id
 * @property string $awal_masa_kerja
 * @property string $akhir_masa_kerja
 * @property string $no_sk
 * @property string $file_sk
 * @property string $kode_file
 * @property integer $status_aktif
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property resource $updated_by
 * @property string $created_at
 * @property string $created_by
 *
 * @property HrdxPegawai $pegawai
 * @property InstStrukturJabatan $strukturJabatan
 * @property RakxProgram[] $rakxPrograms
 * @property RakxProgram[] $rakxPrograms0
 * @property RakxProgram[] $rakxPrograms1
 * @property RakxProgram[] $rakxPrograms2
 * @property RakxReviewProgram[] $rakxReviewPrograms
 */
class Pejabat extends \yii\db\ActiveRecord
{

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
        return 'inst_pejabat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pegawai_id', 'struktur_jabatan_id', 'status_aktif', 'deleted'], 'integer'],
            [['awal_masa_kerja', 'akhir_masa_kerja', 'deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['file_sk'], 'string'],
            [['no_sk'], 'string', 'max' => 45],
            [['kode_file'], 'string', 'max' => 200],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrdxPegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
            [['struktur_jabatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstStrukturJabatan::className(), 'targetAttribute' => ['struktur_jabatan_id' => 'struktur_jabatan_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pejabat_id' => 'Pejabat ID',
            'pegawai_id' => 'Pegawai ID',
            'struktur_jabatan_id' => 'Struktur Jabatan ID',
            'awal_masa_kerja' => 'Awal Masa Kerja',
            'akhir_masa_kerja' => 'Akhir Masa Kerja',
            'no_sk' => 'No Sk',
            'file_sk' => 'File Sk',
            'kode_file' => 'Kode File',
            'status_aktif' => 'Status Aktif',
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
    public function getPegawai()
    {
        return $this->hasOne(HrdxPegawai::className(), ['pegawai_id' => 'pegawai_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStrukturJabatan()
    {
        return $this->hasOne(InstStrukturJabatan::className(), ['struktur_jabatan_id' => 'struktur_jabatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRakxPrograms()
    {
        return $this->hasMany(RakxProgram::className(), ['direvisi_oleh' => 'pejabat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRakxPrograms0()
    {
        return $this->hasMany(RakxProgram::className(), ['disetujui_oleh' => 'pejabat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRakxPrograms1()
    {
        return $this->hasMany(RakxProgram::className(), ['ditolak_oleh' => 'pejabat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRakxPrograms2()
    {
        return $this->hasMany(RakxProgram::className(), ['diusulkan_oleh' => 'pejabat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRakxReviewPrograms()
    {
        return $this->hasMany(RakxReviewProgram::className(), ['pejabat_id' => 'pejabat_id']);
    }
}
