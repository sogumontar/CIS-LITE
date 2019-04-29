<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "cist_waktu_cuti_tahunan".
 *
 * @property integer $waktu_cuti_tahunan_id
 * @property integer $permohonan_cuti_tahunan_id
 * @property string $durasi
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 *
 * @property CistPermohonanCutiTahunan $permohonanCutiTahunan
 */
class WaktuCutiTahunan extends \yii\db\ActiveRecord
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
        return 'cist_waktu_cuti_tahunan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permohonan_cuti_tahunan_id', 'deleted'], 'integer'],
            [['durasi', 'deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            [['permohonan_cuti_tahunan_id'], 'exist', 'skipOnError' => true, 'targetClass' => PermohonanCutiTahunan::className(), 'targetAttribute' => ['permohonan_cuti_tahunan_id' => 'permohonan_cuti_tahunan_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'waktu_cuti_tahunan_id' => 'Waktu Cuti Tahunan ID',
            'permohonan_cuti_tahunan_id' => 'Permohonan Cuti Tahunan ID',
            'durasi' => 'Durasi',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermohonanCutiTahunan()
    {
        return $this->hasOne(PermohonanCutiTahunan::className(), ['permohonan_cuti_tahunan_id' => 'permohonan_cuti_tahunan_id']);
    }
}
