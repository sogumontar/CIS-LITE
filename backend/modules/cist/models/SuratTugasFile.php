<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "cist_surat_tugas_file".
 *
 * @property integer $surat_tugas_file_id
 * @property string $nama_file
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 *
 * @property CistSuratTugas[] $cistSuratTugas
 */
class SuratTugasFile extends \yii\db\ActiveRecord
{
    public $file;

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
        return 'cist_surat_tugas_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['surat_tugas_file_id', 'deleted', 'surat_tugas_id'], 'integer'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['nama_file', 'lokasi_file'], 'string', 'max' => 100],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            [['surat_tugas_id'], 'exist', 'skipOnError' => true, 'targetClass' => SuratTugas::className(), 'targetAttribute' => ['surat_tugas_id' => 'surat_tugas_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'surat_tugas_file_id' => 'Id File',
            'nama_file' => 'Nama File',
            'lokasi_file' => 'Lokasi File',
            'surat_tugas_id' => 'Id Surat Tugas',
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
    public function getSuratTugas()
    {
        return $this->hasMany(SuratTugas::className(), ['surat_tugas_id' => 'surat_tugas_id']);
    }
}
