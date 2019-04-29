<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "cist_status_izin".
 *
 * @property integer $status_izin_id
 * @property integer $permohonan_izin_id
 * @property integer $status_by_atasan
 * @property integer $status_by_wr2
 * @property integer $status_by_hrd
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 *
 * @property PermohonanIzin[] $PermohonanIzins
 * @property Status $statusByAtasan
 * @property Status $statusByWr2
 * @property Status $statusByHrd
 * @property PermohonanIzin $permohonanIzin
 */
class StatusIzin extends \yii\db\ActiveRecord
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
        return 'cist_status_izin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permohonan_izin_id', 'status_by_atasan', 'status_by_wr2', 'status_by_hrd', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['status_by_atasan'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_by_atasan' => 'status_id']],
            [['status_by_wr2'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_by_wr2' => 'status_id']],
            [['status_by_hrd'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_by_hrd' => 'status_id']],
            [['permohonan_izin_id'], 'exist', 'skipOnError' => true, 'targetClass' => PermohonanIzin::className(), 'targetAttribute' => ['permohonan_izin_id' => 'permohonan_izin_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status_izin_id' => 'Status Izin ID',
            'permohonan_izin_id' => 'Permohonan Izin ID',
            'status_by_atasan' => 'Status By Atasan',
            'status_by_wr2' => 'Status By Wr2',
            'status_by_hrd' => 'Status By Hrd',
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
    public function getPermohonanIzins()
    {
        return $this->hasMany(PermohonanIzin::className(), ['status_izin_id' => 'status_izin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusByAtasan()
    {
        return $this->hasOne(Status::className(), ['status_id' => 'status_by_atasan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusByWr2()
    {
        return $this->hasOne(Status::className(), ['status_id' => 'status_by_wr2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusByHrd()
    {
        return $this->hasOne(Status::className(), ['status_id' => 'status_by_hrd']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermohonanIzin()
    {
        return $this->hasOne(PermohonanIzin::className(), ['permohonan_izin_id' => 'permohonan_izin_id']);
    }
}
