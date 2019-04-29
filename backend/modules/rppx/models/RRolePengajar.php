<?php

namespace backend\modules\rppx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "mref_r_role_pengajar".
 *
 * @property integer $role_pengajar_id
 * @property string $nama
 * @property string $desc
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property AdakPenugasanPengajaran[] $adakPenugasanPengajarans
 * @property RppxPenugasanPengajaran[] $rppxPenugasanPengajarans
 */
class RRolePengajar extends \yii\db\ActiveRecord
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
        return 'mref_r_role_pengajar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['desc'], 'string'],
            [['deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['nama'], 'string', 'max' => 45],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_pengajar_id' => 'Role Pengajar ID',
            'nama' => 'Nama',
            'desc' => 'Desc',
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
    public function getAdakPenugasanPengajarans()
    {
        return $this->hasMany(AdakPenugasanPengajaran::className(), ['role_pengajar_id' => 'role_pengajar_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRppxPenugasanPengajarans()
    {
        return $this->hasMany(RppxPenugasanPengajaran::className(), ['role_pengajar_id' => 'role_pengajar_id']);
    }
}
