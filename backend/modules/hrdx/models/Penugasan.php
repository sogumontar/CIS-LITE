<?php

namespace backend\modules\hrdx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "adak_penugasan_pengajaran".
 *
 * @property integer $penugasan_pengajaran_id
 * @property integer $pengajaran_id
 * @property integer $pegawai_id
 * @property integer $role_pengajar_id
 * @property integer $is_fulltime
 * @property string $start_date
 * @property string $end_date
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 */
class Penugasan extends \yii\db\ActiveRecord
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
        return 'adak_penugasan_pengajaran';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pengajaran_id', 'pegawai_id', 'role_pengajar_id', 'is_fulltime', 'deleted'], 'integer'],
            [['role_pengajar_id'], 'required'],
            [['start_date', 'end_date', 'deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'penugasan_pengajaran_id' => 'Penugasan Pengajaran ID',
            'pengajaran_id' => 'Pengajaran ID',
            'pegawai_id' => 'Pegawai ID',
            'role_pengajar_id' => 'Role Pengajar ID',
            'is_fulltime' => 'Is Fulltime',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
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
    public function getPengajaran()
    {
        return $this->hasOne(Pengajaran::className(), ['pengajaran_id' => 'pengajaran_id']);
    }
}
