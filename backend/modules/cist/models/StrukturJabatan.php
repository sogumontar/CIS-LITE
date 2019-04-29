<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "inst_struktur_jabatan".
 *
 * @property integer $struktur_jabatan_id
 * @property integer $instansi_id
 * @property string $jabatan
 * @property integer $parent
 * @property string $inisial
 * @property integer $is_multi_tenant
 * @property integer $mata_anggaran
 * @property integer $laporan
 * @property integer $unit_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property resource $updated_by
 * @property string $created_at
 * @property string $created_by
 *
 * @property InstPejabat[] $instPejabats
 * @property InstInstansi $instansi
 * @property StrukturJabatan $parent0
 * @property StrukturJabatan[] $strukturJabatans
 * @property InstUnit $unit
 * @property InstUnit[] $instUnits
 * @property RakxProgram[] $rakxPrograms
 * @property RakxStrukturJabatanHasMataAnggaran[] $rakxStrukturJabatanHasMataAnggarans
 */
class StrukturJabatan extends \yii\db\ActiveRecord
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
        return 'inst_struktur_jabatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['instansi_id', 'parent', 'is_multi_tenant', 'mata_anggaran', 'laporan', 'unit_id', 'deleted'], 'integer'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['jabatan'], 'string', 'max' => 255],
            [['inisial'], 'string', 'max' => 45],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            [['instansi_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstInstansi::className(), 'targetAttribute' => ['instansi_id' => 'instansi_id']],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => StrukturJabatan::className(), 'targetAttribute' => ['parent' => 'struktur_jabatan_id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => InstUnit::className(), 'targetAttribute' => ['unit_id' => 'unit_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'struktur_jabatan_id' => 'Struktur Jabatan ID',
            'instansi_id' => 'Instansi ID',
            'jabatan' => 'Jabatan',
            'parent' => 'Parent',
            'inisial' => 'Inisial',
            'is_multi_tenant' => 'Is Multi Tenant',
            'mata_anggaran' => 'Mata Anggaran',
            'laporan' => 'Laporan',
            'unit_id' => 'Unit ID',
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
    public function getInstPejabats()
    {
        return $this->hasMany(InstPejabat::className(), ['struktur_jabatan_id' => 'struktur_jabatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstansi()
    {
        return $this->hasOne(InstInstansi::className(), ['instansi_id' => 'instansi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent0()
    {
        return $this->hasOne(StrukturJabatan::className(), ['struktur_jabatan_id' => 'parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStrukturJabatans()
    {
        return $this->hasMany(StrukturJabatan::className(), ['parent' => 'struktur_jabatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(InstUnit::className(), ['unit_id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstUnits()
    {
        return $this->hasMany(InstUnit::className(), ['kepala' => 'struktur_jabatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRakxPrograms()
    {
        return $this->hasMany(RakxProgram::className(), ['dilaksanakan_oleh' => 'struktur_jabatan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRakxStrukturJabatanHasMataAnggarans()
    {
        return $this->hasMany(RakxStrukturJabatanHasMataAnggaran::className(), ['struktur_jabatan_id' => 'struktur_jabatan_id']);
    }
}
