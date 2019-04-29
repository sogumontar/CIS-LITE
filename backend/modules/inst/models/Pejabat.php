<?php

namespace backend\modules\inst\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;
use backend\modules\inst\models\Pegawai;

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
 * @property PdrkAnggaran[] $pdrkAnggarans
 * @property PdrkAnggaran[] $pdrkAnggarans0
 * @property PdrkRealisasiProgram[] $pdrkRealisasiPrograms
 * @property PdrkRealisasiProgram[] $pdrkRealisasiPrograms0
 * @property PdrkRevisiRealisasi[] $pdrkRevisiRealisasis
 * @property PdrkRevisiRencanaProgram[] $pdrkRevisiRencanaPrograms
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
            [['pegawai_id', 'struktur_jabatan_id', 'awal_masa_kerja', 'akhir_masa_kerja', 'no_sk'], 'required'],
            [['pegawai_id', 'struktur_jabatan_id', 'deleted', 'status_aktif'], 'integer'],
            [['file_sk_temp', 'file_sk', 'awal_masa_kerja', 'akhir_masa_kerja', 'deleted_at', 'updated_at', 'created_at'], 'safe'],
            //[['no_sk', 'file_sk'], 'string', 'max' => 45],
            [['kode_file'], 'string', 'max' => 200],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            [['pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['pegawai_id' => 'pegawai_id']],
            [['struktur_jabatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => StrukturJabatan::className(), 'targetAttribute' => ['struktur_jabatan_id' => 'struktur_jabatan_id']],
            [['akhir_masa_kerja'], 'isLowerThan'],
            [['pegawai_id'], 'isPegawaiValid'],
            [['struktur_jabatan_id'], 'isJabatanValid'],
            //[['file_sk'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf'],
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

    public function isJabatanValid($attribute, $params)
    {
        if($this->awal_masa_kerja <= date('Y-m-d') && $this->akhir_masa_kerja >= date('Y-m-d')){
            //validasi untuk single tenant
            $pejabats = Pejabat::find()->select(['struktur_jabatan_id'])->where(['status_aktif' => 1])->andWhere('deleted != 1')->all(); 
            $isSingle = StrukturJabatan::find()->select(['struktur_jabatan_id'])->where(['is_multi_tenant' => 0])->andWhere(['in', 'struktur_jabatan_id', $pejabats])->andWhere('deleted != 1')->All();
            $struktur_jabatan = StrukturJabatan::find()->where(['struktur_jabatan_id' => $this->struktur_jabatan_id])->andWhere(['in', 'struktur_jabatan_id', $isSingle])->andWhere('deleted != 1')->All();
            if(is_null($this->pejabat_id) && !empty($struktur_jabatan))
                $this->addError($attribute, 'Jabatan berupa Single Tenant dan tengah dijabat oleh Pegawai lain !');
        }else if($this->awal_masa_kerja > date('Y-m-d') && $this->akhir_masa_kerja > date('Y-m-d')){
            $jab = StrukturJabatan::find()->where(['struktur_jabatan_id' => $this->struktur_jabatan_id])->andWhere('deleted != 1')->one();
            if($jab->is_multi_tenant == 0){
                //$jabatans = StrukturJabatan::find()->select(['struktur_jabatan_id'])->where(['is_multi_tenant' => 0])->andWhere('deleted != 1')->all();
                $pejabats = Pejabat::find()->where(['struktur_jabatan_id' => $this->struktur_jabatan_id])->andWhere(['status_aktif' => 0])->andWhere(['>', 'awal_masa_kerja', date('Y-m-d')])->andWhere(['>', 'akhir_masa_kerja', date('Y-m-d')])->andWhere('deleted != 1')->all();
                if(is_null($this->pejabat_id) && !empty($pejabats))
                    $this->addError($attribute, 'Jabatan berupa Single Tenant dan akan segera dijabat oleh seorang Pegawai !');
            }else if($jab->is_multi_tenant == 1){
                //$jabatans = StrukturJabatan::find()->select(['struktur_jabatan_id'])->where(['is_multi_tenant' => 1])->andWhere('deleted != 1')->all();
                $pejabats = Pejabat::find()->where(['pegawai_id' => $this->pegawai_id, 'struktur_jabatan_id' => $this->struktur_jabatan_id])->andWhere(['status_aktif' => 0])->andWhere(['>', 'awal_masa_kerja', date('Y-m-d')])->andWhere(['>', 'akhir_masa_kerja', date('Y-m-d')])->andWhere('deleted != 1')->all();
                if(is_null($this->pejabat_id) && !empty($pejabats))
                    $this->addError($attribute, 'Kontrak untuk Jabatan ini sudah diperbaharui !');
            }
        }
    }

    public function isPegawaiValid($attribute, $params)
    {
        if($this->awal_masa_kerja <= date('Y-m-d') && $this->akhir_masa_kerja >= date('Y-m-d')){
            $pejabat = Pejabat::find()->where(['pegawai_id' => $this->pegawai_id, 'struktur_jabatan_id' => $this->struktur_jabatan_id, 'status_aktif' => 1])->andWhere('deleted != 1')->all();
            if(is_null($this->pejabat_id) && !empty($pejabat))
                $this->addError($attribute, 'Pegawai sedang aktif menjabat Jabatan '.$pejabat[0]->strukturJabatan['jabatan'].' !');
        }
        $peg = Pegawai::find()->where(['pegawai_id' => $this->pegawai_id])->andWhere('deleted != 1')->one();
        if($peg->status_aktif_pegawai_id!=1 && $peg->status_aktif_pegawai_id!=2)
            $this->addError($attribute, "Pegawai dalam Status ".$peg->statusAktifPegawai->desc.", sehingga tidak bisa ditambahkan Jabatan !");
    }

    public function isLowerThan($attribute, $params)
    {
        if(strtotime($this->akhir_masa_kerja) <= strtotime($this->awal_masa_kerja))
            $this->addError($attribute, 'Akhir Masa Kerja tidak valid!');
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
    public function getStrukturJabatan()
    {
        return $this->hasOne(StrukturJabatan::className(), ['struktur_jabatan_id' => 'struktur_jabatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnggarans()
    {
        return $this->hasMany(Anggaran::className(), ['pembuat_anggaran' => 'pejabat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnggarans0()
    {
        return $this->hasMany(Anggaran::className(), ['penyetuju_anggaran' => 'pejabat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRealisasiPrograms()
    {
        return $this->hasMany(RealisasiProgram::className(), ['perealisasi' => 'pejabat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRealisasiPrograms0()
    {
        return $this->hasMany(RealisasiProgram::className(), ['approve_by' => 'pejabat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevisiRealisasis()
    {
        return $this->hasMany(RevisiRealisasi::className(), ['perevisi' => 'pejabat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRevisiRencanaPrograms()
    {
        return $this->hasMany(RevisiRencanaProgram::className(), ['perevisi' => 'pejabat_id']);
    }
}
