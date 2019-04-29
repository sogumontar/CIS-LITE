<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "cist_atasan_surat_tugas".
 *
 * @property integer $atasan_surat_tugas_id
 * @property integer $surat_tugas_id
 * @property integer $id_pegawai
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 *
 * @property CistSuratTugas $idSuratTugas
 */
class AtasanSuratTugas extends \yii\db\ActiveRecord
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
        return 'cist_atasan_surat_tugas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['surat_tugas_id', 'id_pegawai', 'perequest', 'deleted'], 'integer'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            [['surat_tugas_id'], 'exist', 'skipOnError' => true, 'targetClass' => SuratTugas::className(), 'targetAttribute' => ['surat_tugas_id' => 'surat_tugas_id']],
            [['id_pegawai'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['id_pegawai' => 'pegawai_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'atasan_surat_tugas_id' => 'Id Atasan Surat Tugas',
            'surat_tugas_id' => 'Id Surat Tugas',
            'id_pegawai' => 'Atasan',
            'perequest' => 'Pemohon',
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
    public function getIdSuratTugas()
    {
        return $this->hasOne(SuratTugas::className(), ['surat_tugas_id' => 'surat_tugas_id']);
    }

    public function getAtasan()
    {
        return $this->hasOne(Pegawai::className(), ['pegawai_id' => 'id_pegawai']);
    }
}
