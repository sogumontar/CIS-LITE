<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_izin_bermalam".
 *
 * @property integer $izin_bermalam_id
 * @property string $rencana_berangkat
 * @property string $rencana_kembali
 * @property string $realisasi_berangkat
 * @property string $realisasi_kembali
 * @property string $desc
 * @property string $tujuan
 * @property integer $dim_id
 * @property integer $keasramaan_id
 * @property integer $status_request_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property Pegawai $keasramaan
 * @property StatusRequest $statusRequest
 * @property Dim $dim
 */
class IzinBermalam extends \yii\db\ActiveRecord
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
        return 'askm_izin_bermalam';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rencana_berangkat', 'rencana_kembali', 'desc', 'tujuan', 'dim_id'], 'required'],
            [['rencana_berangkat', 'rencana_kembali', 'realisasi_berangkat', 'realisasi_kembali', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['desc'], 'string'],
            [['dim_id', 'keasramaan_id', 'status_request_id', 'deleted'], 'integer'],
            [['tujuan'], 'string', 'max' => 45],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['keasramaan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['keasramaan_id' => 'pegawai_id']],
            [['status_request_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusRequest::className(), 'targetAttribute' => ['status_request_id' => 'status_request_id']],
            [['dim_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dim::className(), 'targetAttribute' => ['dim_id' => 'dim_id']],
            [['rencana_kembali'], 'isAfterBerangkat'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'izin_bermalam_id' => 'Izin Bermalam ID',
            'rencana_berangkat' => 'Rencana Berangkat',
            'rencana_kembali' => 'Rencana Kembali',
            'realisasi_berangkat' => 'Realisasi Berangkat',
            'realisasi_kembali' => 'Realisasi Kembali',
            'desc' => 'Keperluan IB',
            'tujuan' => 'Tempat Tujuan',
            'dim_id' => 'NIM Mahasiswa',
            'keasramaan_id' => 'Keasramaan ID',
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

    public function isAfterBerangkat($attribute, $params)
    {
        if(strtotime($this->rencana_kembali)<=strtotime($this->rencana_berangkat))
            $this->addError($attribute, 'Tidak boleh lebih awal atau sama dengan Rencana Berangkat !');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(), ['pegawai_id' => 'keasramaan_id']);
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
