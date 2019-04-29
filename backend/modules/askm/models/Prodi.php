<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;
use backend\modules\askm\models\Jenjang;

/**
 * This is the model class for table "inst_prodi".
 *
 * @property integer $ref_kbk_id
 * @property string $kbk_id
 * @property string $kpt_id
 * @property integer $jenjang_id
 * @property string $kbk_ind
 * @property string $singkatan_prodi
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
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 *
 * @property DimxDim[] $dimxDims
 * @property HrdxDosen[] $hrdxDosens
 * @property HrdxPegawai[] $hrdxPegawais
 * @property HrdxStaf[] $hrdxStafs
 * @property InstRJenjang $jenjang
 * @property KrkmKuliah[] $krkmKuliahs
 * @property KrkmKuliahProdi[] $krkmKuliahProdis
 * @property KrkmKurikulumProdi[] $krkmKurikulumProdis
 */
class Prodi extends \yii\db\ActiveRecord
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
        return 'inst_prodi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jenjang_id', 'status', 'is_jenjang_all', 'is_public', 'is_hidden', 'deleted'], 'integer'],
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
    public function getJenjangId()
    {
        return $this->hasOne(Jenjang::className(), ['jenjang_id' => 'jenjang_id']);
    }
    
}
