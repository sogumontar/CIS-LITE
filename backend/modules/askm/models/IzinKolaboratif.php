<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_izin_kolaboratif".
 *
 * @property integer $izin_kolaboratif_id
 * @property string $rencana_mulai
 * @property string $rencana_berakhir
 * @property string $batas_waktu
 * @property string $desc
 * @property integer $dim_id
 * @property integer $status_request_id
 * @property integer $baak_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property Pegawai $baak
 * @property StatusRequest $statusRequest
 * @property Dim $dim
 */
class IzinKolaboratif extends \yii\db\ActiveRecord
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
        return 'askm_izin_kolaboratif';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rencana_mulai', 'rencana_berakhir', 'batas_waktu', 'desc', 'dim_id'], 'required'],
            [['rencana_mulai', 'rencana_berakhir', 'batas_waktu', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['desc'], 'string'],
            [['dim_id', 'status_request_id', 'baak_id', 'deleted'], 'integer'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
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
            'izin_kolaboratif_id' => 'Izin Kolaboratif ID',
            'rencana_mulai' => 'Rencana Mulai',
            'rencana_berakhir' => 'Rencana Berakhir',
            'batas_waktu' => 'Batas Waktu',
            'desc' => 'Keterangan',
            'dim_id' => 'Dim ID',
            'status_request_id' => 'Status Request ID',
            'baak_id' => 'Baak ID',
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
