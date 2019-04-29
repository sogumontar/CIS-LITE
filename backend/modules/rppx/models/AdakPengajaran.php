<?php

namespace backend\modules\rppx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "adak_pengajaran".
 *
 * @property integer $pengajaran_id
 * @property integer $kuliah_id
 * @property integer $ta
 * @property integer $sem_ta
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_by
 * @property string $updated_at
 *
 * @property AdakMahasiswaAssistant[] $adakMahasiswaAssistants
 * @property KrkmKuliah $kuliah
 * @property AdakPenugasanPengajaran[] $adakPenugasanPengajarans
 * @property PrklKrsDetail[] $prklKrsDetails
 * @property RppxPenugasanPengajaran[] $rppxPenugasanPengajarans
 */
class AdakPengajaran extends \yii\db\ActiveRecord
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
        return 'adak_pengajaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kuliah_id', 'ta', 'sem_ta', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['kuliah_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kuliah::className(), 'targetAttribute' => ['kuliah_id' => 'kuliah_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pengajaran_id' => 'Pengajaran ID',
            'kuliah_id' => 'Kuliah ID',
            'ta' => 'Ta',
            'sem_ta' => 'Sem Ta',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdakMahasiswaAssistants()
    {
        return $this->hasMany(AdakMahasiswaAssistant::className(), ['pengajaran_id' => 'pengajaran_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKuliah()
    {
        return $this->hasOne(Kuliah::className(), ['kuliah_id' => 'kuliah_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdakPenugasanPengajarans()
    {
        return $this->hasMany(AdakPenugasanPengajaran::className(), ['pengajaran_id' => 'pengajaran_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklKrsDetails()
    {
        return $this->hasMany(PrklKrsDetail::className(), ['pengajaran_id' => 'pengajaran_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRppxPenugasanPengajarans()
    {
        return $this->hasMany(RppxPenugasanPengajaran::className(), ['pengajaran_id' => 'pengajaran_id']);
    }
}
