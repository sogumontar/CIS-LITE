<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_izin_pengampu".
 *
 * @property integer $id_izin_pengampu
 * @property integer $izin_keluar_id
 * @property integer $dosen_id
 * @property integer $status_request_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property Pegawai $dosen
 * @property StatusRequest $statusRequest
 * @property IzinKeluar $izinKeluar
 */
class IzinPengampu extends \yii\db\ActiveRecord
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
        return 'askm_izin_pengampu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['izin_keluar_id', 'dosen_id', 'status_request_id', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['dosen_id' => 'pegawai_id']],
            [['status_request_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusRequest::className(), 'targetAttribute' => ['status_request_id' => 'status_request_id']],
            [['izin_keluar_id'], 'exist', 'skipOnError' => true, 'targetClass' => IzinKeluar::className(), 'targetAttribute' => ['izin_keluar_id' => 'izin_keluar_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_izin_pengampu' => 'Id Izin Pengampu',
            'izin_keluar_id' => 'Izin Keluar ID',
            'dosen_id' => 'Dosen ID',
            'status_request_id' => 'Status Request ID',
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
    public function getDosen()
    {
        return $this->hasOne(Pegawai::className(), ['pegawai_id' => 'dosen_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusRequest()
    {
        return $this->hasOne(StatusRequest::className(), ['status_request_id' => 'status_request_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIzinKeluar()
    {
        return $this->hasOne(IzinKeluar::className(), ['izin_keluar_id' => 'izin_keluar_id']);
    }
}
