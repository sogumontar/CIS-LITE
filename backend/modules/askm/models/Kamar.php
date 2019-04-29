<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_kamar".
 *
 * @property integer $kamar_id
 * @property string $nomor_kamar
 * @property integer $asrama_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property DimKamar[] $DimKamar
 * @property Asrama $asrama
 */
class Kamar extends \yii\db\ActiveRecord
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
        return 'askm_kamar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asrama_id'], 'required'],
            [['asrama_id', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['nomor_kamar'], 'string', 'max' => 45],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['asrama_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asrama::className(), 'targetAttribute' => ['asrama_id' => 'asrama_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kamar_id' => 'Kamar ID',
            'nomor_kamar' => 'Nomor Kamar',
            'asrama_id' => 'Asrama',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimKamar()
    {
        return $this->hasMany(DimKamar::className(), ['kamar_id' => 'kamar_id'])->where('deleted!=1');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsrama()
    {
        return $this->hasOne(Asrama::className(), ['asrama_id' => 'asrama_id']);
    }
}
