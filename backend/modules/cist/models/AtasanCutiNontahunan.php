<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "cist_atasan_cuti_nontahunan".
 *
 * @property integer $atasan_cuti_id
 * @property integer $pmhnn_cuti_nthn_id
 * @property integer $pegawai_id
 * @property string $nama
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 *
 * @property CistPermohonanCutiNontahunan $pmhnnCutiNthn
 */
class AtasanCutiNontahunan extends \yii\db\ActiveRecord
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
        return 'cist_atasan_cuti_nontahunan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permohonan_cuti_nontahunan_id'], 'required'],
            [['permohonan_cuti_nontahunan_id', 'pegawai_id', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['permohonan_cuti_nontahunan_id'], 'exist', 'skipOnError' => true, 'targetClass' => PermohonanCutiNontahunan::className(), 'targetAttribute' => ['permohonan_cuti_nontahunan_id' => 'permohonan_cuti_nontahunan_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'atasan_cuti_id' => 'Atasan Cuti ID',
            'permohonan_cuti_nontahunan_id' => 'Permohonan Cuti Nontahunan ID',
            'pegawai_id' => 'Pegawai ID',
            'name' => 'Nama',
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
    public function getPmhnnCutiNthn()
    {
        return $this->hasOne(PermohonanCutiNontahunan::className(), ['permohonan_cuti_nontahunan_id' => 'permohonan_cuti_nontahunan_id']);
    }
}
