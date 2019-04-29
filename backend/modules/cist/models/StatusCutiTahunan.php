<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "cist_status_cuti_tahunan".
 *
 * @property integer $status_cuti_tahunan_id
 * @property integer $permohonan_cuti_tahunan_id
 * @property integer $status_by_hrd
 * @property integer $status_by_atasan
 * @property integer $status_by_wr2
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 *
 * @property PermohonanCutiTahunan[] $PermohonanCutiTahunans
 * @property PermohonanCutiTahunan $permohonanCutiTahunan
 * @property Status $statusByHrd
 * @property Status $statusByAtasan
 * @property Status $statusByWr2
 */
class StatusCutiTahunan extends \yii\db\ActiveRecord
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
        return 'cist_status_cuti_tahunan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permohonan_cuti_tahunan_id'], 'required'],
            [['permohonan_cuti_tahunan_id', 'status_by_hrd', 'status_by_atasan', 'status_by_wr2', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['permohonan_cuti_tahunan_id'], 'exist', 'skipOnError' => true, 'targetClass' => PermohonanCutiTahunan::className(), 'targetAttribute' => ['permohonan_cuti_tahunan_id' => 'permohonan_cuti_tahunan_id']],
            [['status_by_hrd'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_by_hrd' => 'status_id']],
            [['status_by_atasan'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_by_atasan' => 'status_id']],
            [['status_by_wr2'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_by_wr2' => 'status_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status_cuti_tahunan_id' => 'Status Cuti Tahunan ID',
            'permohonan_cuti_tahunan_id' => 'Permohonan Cuti Tahunan ID',
            'status_by_hrd' => 'Status By Hrd',
            'status_by_atasan' => 'Status By Atasan',
            'status_by_wr2' => 'Status By Wr2',
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
    public function getPermohonanCutiTahunans()
    {
        return $this->hasMany(PermohonanCutiTahunan::className(), ['status_izin_id' => 'status_cuti_tahunan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermohonanCutiTahunan()
    {
        return $this->hasOne(PermohonanCutiTahunan::className(), ['permohonan_cuti_tahunan_id' => 'permohonan_cuti_tahunan_id']);
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
}
