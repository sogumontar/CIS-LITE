<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_izin_ruangan".
 *
 * @property integer $izin_ruangan_id
 * @property string $rencana_mulai
 * @property string $rencana_berakhir
 * @property string $desc
 * @property integer $dim_id
 * @property integer $baak_id
 * @property integer $lokasi_id
 * @property integer $status_request_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property Lokasi $lokasi
 * @property Pegawai $baak
 * @property StatusRequest $statusRequest
 * @property Dim $dim
 */
class IzinRuangan extends \yii\db\ActiveRecord
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
        return 'askm_izin_ruangan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rencana_mulai', 'rencana_berakhir', 'desc', 'dim_id'], 'required'],
            [['rencana_mulai', 'rencana_berakhir', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['desc'], 'string'],
            [['dim_id', 'baak_id', 'lokasi_id', 'status_request_id', 'deleted'], 'integer'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['lokasi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lokasi::className(), 'targetAttribute' => ['lokasi_id' => 'lokasi_id']],
            [['baak_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['baak_id' => 'pegawai_id']],
            [['status_request_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusRequest::className(), 'targetAttribute' => ['status_request_id' => 'status_request_id']],
            [['dim_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dim::className(), 'targetAttribute' => ['dim_id' => 'dim_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'izin_ruangan_id' => 'Izin Ruangan ID',
            'rencana_mulai' => 'Rencana Mulai',
            'rencana_berakhir' => 'Rencana Berakhir',
            'desc' => 'Keterangan',
            'dim_id' => 'Dim ID',
            'baak_id' => 'Baak ID',
            'lokasi_id' => 'Lokasi',
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
    public function getLokasi()
    {
        return $this->hasOne(Lokasi::className(), ['lokasi_id' => 'lokasi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaak()
    {
        return $this->hasOne(Pegawai::className(), ['pegawai_id' => 'baak_id']);
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
    public function getDim()
    {
        return $this->hasOne(Dim::className(), ['dim_id' => 'dim_id']);
    }
}
