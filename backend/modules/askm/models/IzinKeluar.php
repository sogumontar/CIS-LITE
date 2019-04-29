<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "askm_izin_keluar".
 *
 * @property integer $izin_keluar_id
 * @property string $rencana_berangkat
 * @property string $rencana_kembali
 * @property string $realisasi_berangkat
 * @property string $realisasi_kembali
 * @property string $desc
 * @property integer $dim_id
 * @property integer $dosen_wali_id
 * @property integer $baak_id
 * @property integer $keasramaan_id
 * @property integer $status_request_baak
 * @property integer $status_request_keasramaan
 * @property integer $status_request_dosen_wali
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 *
 * @property StatusRequest $statusRequestKeasramaan
 * @property StatusRequest $statusRequestBaak
 * @property Pegawai $dosen
 * @property Pegawai $keasramaan
 * @property Pegawai $Baak
 * @property StatusRequest $statusRequestDosen
 * @property Dim $dim
 */
class IzinKeluar extends \yii\db\ActiveRecord
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
        return 'askm_izin_keluar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rencana_berangkat', 'rencana_kembali', 'desc', 'dim_id'], 'required'],
            [['rencana_berangkat', 'rencana_kembali', 'realisasi_berangkat', 'realisasi_kembali', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['desc'], 'string'],
            [['dim_id', 'dosen_wali_id', 'baak_id', 'keasramaan_id', 'status_request_baak', 'status_request_keasramaan', 'status_request_dosen_wali', 'deleted'], 'integer'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32],
            [['status_request_keasramaan'], 'exist', 'skipOnError' => true, 'targetClass' => StatusRequest::className(), 'targetAttribute' => ['status_request_keasramaan' => 'status_request_id']],
            [['status_request_baak'], 'exist', 'skipOnError' => true, 'targetClass' => StatusRequest::className(), 'targetAttribute' => ['status_request_baak' => 'status_request_id']],
            [['dosen_wali_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['dosen_wali_id' => 'pegawai_id']],
            [['keasramaan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['keasramaan_id' => 'pegawai_id']],
            [['baak_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['baak_id' => 'pegawai_id']],
            [['status_request_dosen_wali'], 'exist', 'skipOnError' => true, 'targetClass' => StatusRequest::className(), 'targetAttribute' => ['status_request_dosen_wali' => 'status_request_id']],
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
            'izin_keluar_id' => 'Izin Keluar ID',
            'rencana_berangkat' => 'Rencana Berangkat',
            'rencana_kembali' => 'Rencana Kembali',
            'realisasi_berangkat' => 'Realisasi Berangkat',
            'realisasi_kembali' => 'Realisasi Kembali',
            'desc' => 'Keperluan IK',
            'dim_id' => 'Dim ID',
            'dosen_wali_id' => 'Dosen ID',
            'baak_id' => 'Baak ID',
            'keasramaan_id' => 'Keasramaan ID',
            'status_request_baak' => 'Status Request(Baak)',
            'status_request_keasramaan' => 'Status Request(Keasramaan)',
            'status_request_dosen_wali' => 'Status Request(Dosen)',
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
    public function getStatusRequestKeasramaan()
    {
        return $this->hasOne(StatusRequest::className(), ['status_request_id' => 'status_request_keasramaan']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatusRequestBaak()
    {
        return $this->hasOne(StatusRequest::className(), ['status_request_id' => 'status_request_baak']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDosen()
    {
        return $this->hasOne(Pegawai::className(), ['pegawai_id' => 'dosen_wali_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeasramaan()
    {
        return $this->hasOne(Pegawai::className(), ['pegawai_id' => 'keasramaan_id']);
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
    public function getStatusRequestDosen()
    {
        return $this->hasOne(StatusRequest::className(), ['status_request_id' => 'status_request_dosen_wali']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDim()
    {
        return $this->hasOne(Dim::className(), ['dim_id' => 'dim_id']);
    }
}
