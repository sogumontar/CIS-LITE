<?php

namespace backend\modules\rppx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "adak_kelas".
 *
 * @property integer $kelas_id
 * @property integer $ta
 * @property string $nama
 * @property string $ket
 * @property integer $dosen_wali_id
 * @property integer $prodi_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property HrdxDosen $dosenWali
 * @property InstProdi $prodi
 * @property AdakRegistrasi[] $adakRegistrasis
 * @property JdwlJadwal[] $jdwlJadwals
 * @property PrklBeritaAcaraKuliah[] $prklBeritaAcaraKuliahs
 * @property RppxPenugasanPengajaran[] $rppxPenugasanPengajarans
 * @property SchdJadwalKuliah[] $schdJadwalKuliahs
 */
class Kelas extends \yii\db\ActiveRecord
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
        return 'adak_kelas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ta', 'dosen_wali_id', 'prodi_id', 'deleted'], 'integer'],
            [['ket'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['nama'], 'string', 'max' => 20],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32],
            [['dosen_wali_id'], 'exist', 'skipOnError' => true, 'targetClass' => HrdxDosen::className(), 'targetAttribute' => ['dosen_wali_id' => 'dosen_id']],
            [['prodi_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstProdi::className(), 'targetAttribute' => ['prodi_id' => 'ref_kbk_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kelas_id' => 'Kelas ID',
            'ta' => 'Ta',
            'nama' => 'Nama',
            'ket' => 'Ket',
            'dosen_wali_id' => 'Dosen Wali ID',
            'prodi_id' => 'Prodi ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosenWali()
    {
        return $this->hasOne(HrdxDosen::className(), ['dosen_id' => 'dosen_wali_id']);
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
    public function getAdakRegistrasis()
    {
        return $this->hasMany(AdakRegistrasi::className(), ['kelas_id' => 'kelas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJdwlJadwals()
    {
        return $this->hasMany(JdwlJadwal::className(), ['kelas_id' => 'kelas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklBeritaAcaraKuliahs()
    {
        return $this->hasMany(PrklBeritaAcaraKuliah::className(), ['kelas_id' => 'kelas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRppxPenugasanPengajarans()
    {
        return $this->hasMany(RppxPenugasanPengajaran::className(), ['kelas' => 'kelas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchdJadwalKuliahs()
    {
        return $this->hasMany(SchdJadwalKuliah::className(), ['kelas_id' => 'kelas_id']);
    }
}
