<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "mref_r_status_ikatan_kerja_pegawai".
 *
 * @property integer $status_ikatan_kerja_pegawai_id
 * @property string $nama
 * @property string $desc
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 *
 * @property HrdxPegawai[] $hrdxPegawais
 */
class StatusIkatanKerjaPegawai extends \yii\db\ActiveRecord
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
        return 'mref_r_status_ikatan_kerja_pegawai';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['desc'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['deleted'], 'integer'],
            [['nama'], 'string', 'max' => 45],
            [['created_by', 'updated_by', 'deleted_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status_ikatan_kerja_pegawai_id' => 'Status Ikatan Kerja Pegawai ID',
            'nama' => 'Nama',
            'desc' => 'Desc',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHrdxPegawais()
    {
        return $this->hasMany(HrdxPegawai::className(), ['status_ikatan_kerja_pegawai_id' => 'status_ikatan_kerja_pegawai_id']);
    }
}
