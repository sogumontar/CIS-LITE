<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "cist_laporan_surat_tugas".
 *
 * @property integer $laporan_surat_tugas_id
 * @property string $nama_file
 * @property string $tanggal_submit
 * @property integer $surat_tugas_id
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 * @property string $batas_submit
 *
 * @property CistSuratTugas $idSuratTugas
 */
class LaporanSuratTugas extends \yii\db\ActiveRecord
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
        return 'cist_laporan_surat_tugas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tanggal_submit', 'deleted_at', 'updated_at', 'created_at', 'batas_submit'], 'safe'],
            [['surat_tugas_id', 'batas_submit'], 'required'],
            [['surat_tugas_id', 'deleted', 'status_id'], 'integer'],
            [['review_laporan'], 'string'],
            [['nama_file', 'lokasi_file'], 'string', 'max' => 200],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            [['surat_tugas_id'], 'exist', 'skipOnError' => true, 'targetClass' => SuratTugas::className(), 'targetAttribute' => ['surat_tugas_id' => 'surat_tugas_id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::className(), 'targetAttribute' => ['status_id' => 'status_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'laporan_surat_tugas_id' => 'Id Laporan',
            'nama_file' => 'Nama File',
            'lokasi_file' => 'Lokasi File',
            'tanggal_submit' => 'Tanggal Submit',
            'surat_tugas_id' => 'Id Surat Tugas',
            'status_id' => 'status_id',
            'review_laporan' => 'Review Laporan',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'batas_submit' => 'Batas Submit',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSuratTugas()
    {
        return $this->hasOne(SuratTugas::className(), ['surat_tugas_id' => 'surat_tugas_id']);
    }

    public function getStatusLaporan()
    {
        return $this->hasOne(Status::className(), ['status_id' => 'status_id']);
    }
}
