<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "cist_atasan_izin".
 *
 * @property integer $atasan_izin_id
 * @property integer $permohonan_izin_id
 * @property integer $pegawai_id
 * @property string $name
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 *
 * @property PermohonanIzin $permohonanIzin
 */
class AtasanIzin extends \yii\db\ActiveRecord
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
        return 'cist_atasan_izin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permohonan_izin_id', 'pegawai_id', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['permohonan_izin_id'], 'exist', 'skipOnError' => true, 'targetClass' => PermohonanIzin::className(), 'targetAttribute' => ['permohonan_izin_id' => 'permohonan_izin_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'atasan_izin_id' => 'Atasan Izin ID',
            'permohonan_izin_id' => 'Permohonan Izin ID',
            'pegawai_id' => 'Pegawai ID',
            'name' => 'Name',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermohonanIzin()
    {
        return $this->hasOne(PermohonanIzin::className(), ['permohonan_izin_id' => 'permohonan_izin_id']);
    }
}
