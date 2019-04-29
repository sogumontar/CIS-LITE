<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inst_prodi".
 *
 * @property integer $ref_kbk_id
 * @property string $kbk_id
 * @property string $kpt_id
 * @property integer $jenjang_id
 * @property string $kbk_ind
 * @property string $singkatan_prodi
 * @property integer $kepala_id
 * @property string $kbk_ing
 * @property string $nama_kopertis_ind
 * @property string $nama_kopertis_ing
 * @property string $short_desc_ind
 * @property string $short_desc_ing
 * @property string $desc_ind
 * @property string $desc_ing
 * @property integer $status
 * @property integer $is_jenjang_all
 * @property integer $is_public
 * @property integer $is_hidden
 * @property integer $fakultas_id
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 *
 * @property AdakKelas[] $adakKelas
 * @property DimxDim[] $dimxDims
 * @property HrdxDosen[] $hrdxDosens
 * @property HrdxPegawai[] $hrdxPegawais
 * @property HrdxStaf[] $hrdxStafs
 * @property InstRJenjang $jenjang
 * @property InstFakultas $fakultas
 * @property InstPejabat $kepala
 * @property KrkmKuliah[] $krkmKuliahs
 * @property KrkmKuliahProdi[] $krkmKuliahProdis
 * @property KrkmKurikulumProdi[] $krkmKurikulumProdis
 */
class InstProdi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inst_prodi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenjang_id', 'kepala_id', 'status', 'is_jenjang_all', 'is_public', 'is_hidden', 'fakultas_id', 'deleted'], 'integer'],
            [['desc_ind', 'desc_ing'], 'string'],
            [['updated_at', 'deleted_at', 'created_at'], 'safe'],
            [['kbk_id'], 'string', 'max' => 20],
            [['kpt_id'], 'string', 'max' => 10],
            [['kbk_ind', 'kbk_ing'], 'string', 'max' => 100],
            [['singkatan_prodi'], 'string', 'max' => 50],
            [['nama_kopertis_ind', 'nama_kopertis_ing', 'short_desc_ind', 'short_desc_ing'], 'string', 'max' => 255],
            [['created_by', 'deleted_by'], 'string', 'max' => 32],
            [['updated_by'], 'string', 'max' => 45],
            [['kbk_id'], 'unique'],
            [['jenjang_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstRJenjang::className(), 'targetAttribute' => ['jenjang_id' => 'jenjang_id']],
            [['fakultas_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstFakultas::className(), 'targetAttribute' => ['fakultas_id' => 'fakultas_id']],
            [['kepala_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstPejabat::className(), 'targetAttribute' => ['kepala_id' => 'pejabat_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ref_kbk_id' => 'Ref Kbk ID',
            'kbk_id' => 'Kbk ID',
            'kpt_id' => 'Kpt ID',
            'jenjang_id' => 'Jenjang ID',
            'kbk_ind' => 'Kbk Ind',
            'singkatan_prodi' => 'Singkatan Prodi',
            'kepala_id' => 'Kepala ID',
            'kbk_ing' => 'Kbk Ing',
            'nama_kopertis_ind' => 'Nama Kopertis Ind',
            'nama_kopertis_ing' => 'Nama Kopertis Ing',
            'short_desc_ind' => 'Short Desc Ind',
            'short_desc_ing' => 'Short Desc Ing',
            'desc_ind' => 'Desc Ind',
            'desc_ing' => 'Desc Ing',
            'status' => 'Status',
            'is_jenjang_all' => 'Is Jenjang All',
            'is_public' => 'Is Public',
            'is_hidden' => 'Is Hidden',
            'fakultas_id' => 'Fakultas ID',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdakKelas()
    {
        return $this->hasMany(AdakKelas::className(), ['prodi_id' => 'ref_kbk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimxDims()
    {
        return $this->hasMany(DimxDim::className(), ['ref_kbk_id' => 'ref_kbk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrdxDosens()
    {
        return $this->hasMany(HrdxDosen::className(), ['prodi_id' => 'ref_kbk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrdxPegawais()
    {
        return $this->hasMany(HrdxPegawai::className(), ['ref_kbk_id' => 'ref_kbk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrdxStafs()
    {
        return $this->hasMany(HrdxStaf::className(), ['prodi_id' => 'ref_kbk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenjang()
    {
        return $this->hasOne(InstRJenjang::className(), ['jenjang_id' => 'jenjang_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFakultas()
    {
        return $this->hasOne(InstFakultas::className(), ['fakultas_id' => 'fakultas_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKepala()
    {
        return $this->hasOne(InstPejabat::className(), ['pejabat_id' => 'kepala_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKrkmKuliahs()
    {
        return $this->hasMany(KrkmKuliah::className(), ['ref_kbk_id' => 'ref_kbk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKrkmKuliahProdis()
    {
        return $this->hasMany(KrkmKuliahProdi::className(), ['ref_kbk_id' => 'ref_kbk_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKrkmKurikulumProdis()
    {
        return $this->hasMany(KrkmKurikulumProdi::className(), ['ref_kbk_id' => 'ref_kbk_id']);
    }
}
