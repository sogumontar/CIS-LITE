<?php

namespace backend\modules\rppx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "hrdx_dosen".
 *
 * @property integer $dosen_id
 * @property integer $pegawai_id
 * @property string $nidn
 * @property integer $prodi_id
 * @property integer $golongan_kepangkatan_id
 * @property integer $jabatan_akademik_id
 * @property integer $status_ikatan_kerja_dosen_id
 * @property integer $gbk_1
 * @property integer $gbk_2
 * @property string $aktif_start
 * @property string $aktif_end
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $temp_id_old
 *
 * @property AdakKelas[] $adakKelas
 * @property DimxAlumni[] $dimxAlumnis
 * @property DimxAlumni[] $dimxAlumnis0
 * @property MrefRGolonganKepangkatan $golonganKepangkatan
 * @property MrefRGbk $gbk1
 * @property MrefRGbk $gbk2
 * @property MrefRJabatanAkademik $jabatanAkademik
 * @property HrdxPegawai $pegawai
 * @property InstProdi $prodi
 * @property MrefRStatusIkatanKerjaDosen $statusIkatanKerjaDosen
 * @property HrdxRiwayatPendidikanOld[] $hrdxRiwayatPendidikanOlds
 * @property LppmPenelitianDosen[] $lppmPenelitianDosens
 * @property LppmTKetuaGbk[] $lppmTKetuaGbks
 * @property PrklInfoTa[] $prklInfoTas
 * @property PrklInfoTa[] $prklInfoTas0
 */
class Dosen extends \yii\db\ActiveRecord
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
        return 'hrdx_dosen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pegawai_id', 'prodi_id', 'golongan_kepangkatan_id', 'jabatan_akademik_id', 'status_ikatan_kerja_dosen_id', 'gbk_1', 'gbk_2', 'deleted'], 'integer'],
            [['aktif_start', 'aktif_end', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['nidn'], 'string', 'max' => 10],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['temp_id_old'], 'string', 'max' => 100],
            [['golongan_kepangkatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MrefRGolonganKepangkatan::className(), 'targetAttribute' => ['golongan_kepangkatan_id' => 'golongan_kepangkatan_id']],
            [['gbk_1'], 'exist', 'skipOnError' => true, 'targetClass' => MrefRGbk::className(), 'targetAttribute' => ['gbk_1' => 'gbk_id']],
            [['gbk_2'], 'exist', 'skipOnError' => true, 'targetClass' => MrefRGbk::className(), 'targetAttribute' => ['gbk_2' => 'gbk_id']],
            [['jabatan_akademik_id'], 'exist', 'skipOnError' => true, 'targetClass' => MrefRJabatanAkademik::className(), 'targetAttribute' => ['jabatan_akademik_id' => 'jabatan_akademik_id']],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrdxPegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
            [['prodi_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstProdi::className(), 'targetAttribute' => ['prodi_id' => 'ref_kbk_id']],
            [['status_ikatan_kerja_dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => MrefRStatusIkatanKerjaDosen::className(), 'targetAttribute' => ['status_ikatan_kerja_dosen_id' => 'status_ikatan_kerja_dosen_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dosen_id' => 'Dosen ID',
            'pegawai_id' => 'Pegawai ID',
            'nidn' => 'Nidn',
            'prodi_id' => 'Prodi ID',
            'golongan_kepangkatan_id' => 'Golongan Kepangkatan ID',
            'jabatan_akademik_id' => 'Jabatan Akademik ID',
            'status_ikatan_kerja_dosen_id' => 'Status Ikatan Kerja Dosen ID',
            'gbk_1' => 'Gbk 1',
            'gbk_2' => 'Gbk 2',
            'aktif_start' => 'Aktif Start',
            'aktif_end' => 'Aktif End',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'temp_id_old' => 'Temp Id Old',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdakKelas()
    {
        return $this->hasMany(AdakKelas::className(), ['dosen_wali_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimxAlumnis()
    {
        return $this->hasMany(DimxAlumni::className(), ['dosen_id_1' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimxAlumnis0()
    {
        return $this->hasMany(DimxAlumni::className(), ['dosen_id_2' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGolonganKepangkatan()
    {
        return $this->hasOne(MrefRGolonganKepangkatan::className(), ['golongan_kepangkatan_id' => 'golongan_kepangkatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGbk1()
    {
        return $this->hasOne(MrefRGbk::className(), ['gbk_id' => 'gbk_1']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGbk2()
    {
        return $this->hasOne(MrefRGbk::className(), ['gbk_id' => 'gbk_2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJabatanAkademik()
    {
        return $this->hasOne(MrefRJabatanAkademik::className(), ['jabatan_akademik_id' => 'jabatan_akademik_id']);
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
    public function getProdi()
    {
        return $this->hasOne(InstProdi::className(), ['ref_kbk_id' => 'prodi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusIkatanKerjaDosen()
    {
        return $this->hasOne(MrefRStatusIkatanKerjaDosen::className(), ['status_ikatan_kerja_dosen_id' => 'status_ikatan_kerja_dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrdxRiwayatPendidikanOlds()
    {
        return $this->hasMany(HrdxRiwayatPendidikanOld::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLppmPenelitianDosens()
    {
        return $this->hasMany(LppmPenelitianDosen::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLppmTKetuaGbks()
    {
        return $this->hasMany(LppmTKetuaGbk::className(), ['dosen_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklInfoTas()
    {
        return $this->hasMany(PrklInfoTa::className(), ['pembimbing_1' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklInfoTas0()
    {
        return $this->hasMany(PrklInfoTa::className(), ['pembimbing_2' => 'dosen_id']);
    }
}
