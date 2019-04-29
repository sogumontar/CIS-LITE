<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_r_status_request".
 *
 * @property integer $status_request_id
 * @property string $status_request
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property AskmIzinBermalam[] $askmIzinBermalams
 * @property AskmIzinKeluar[] $askmIzinKeluars
 * @property AskmIzinKeluar[] $askmIzinKeluars0
 * @property AskmIzinKeluar[] $askmIzinKeluars1
 * @property AskmIzinKolaboratif[] $askmIzinKolaboratifs
 * @property AskmIzinRuangan[] $askmIzinRuangans
 */
class StatusRequest extends \yii\db\ActiveRecord
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
        return 'askm_r_status_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_request_id', 'status_request'], 'required'],
            [['status_request_id', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['status_request'], 'string', 'max' => 45],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'status_request_id' => 'Status Request ID',
            'status_request' => 'Status Request',
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
    public function getAskmIzinBermalams()
    {
        return $this->hasMany(AskmIzinBermalam::className(), ['status_request_id' => 'status_request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAskmIzinKeluars()
    {
        return $this->hasMany(AskmIzinKeluar::className(), ['status_request_keasramaan' => 'status_request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAskmIzinKeluars0()
    {
        return $this->hasMany(AskmIzinKeluar::className(), ['status_request_baak' => 'status_request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAskmIzinKeluars1()
    {
        return $this->hasMany(AskmIzinKeluar::className(), ['status_request_dosen' => 'status_request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAskmIzinKolaboratifs()
    {
        return $this->hasMany(AskmIzinKolaboratif::className(), ['status_request_id' => 'status_request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAskmIzinRuangans()
    {
        return $this->hasMany(AskmIzinRuangan::className(), ['status_request_id' => 'status_request_id']);
    }
}
