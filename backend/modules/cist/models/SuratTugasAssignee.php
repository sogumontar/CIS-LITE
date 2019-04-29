<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;
use backend\modules\sppd\models\BiayaPerjalanan;

/**
 * This is the model class for table "cist_surat_tugas_assignee".
 *
 * @property integer $surat_tugas_assignee_id
 * @property integer $id_pegawai
 * @property integer $surat_tugas_id
 *
 * @property HrdxPegawai $idPegawai
 * @property CistSuratTugas $idSuratTugas
 */
class SuratTugasAssignee extends \yii\db\ActiveRecord
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
        return 'cist_surat_tugas_assignee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['surat_tugas_assignee_id', 'id_pegawai', 'surat_tugas_id'], 'integer'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32],
            [['id_pegawai'], 'exist', 'skipOnError' => true, 'targetClass' => Pegawai::className(), 'targetAttribute' => ['id_pegawai' => 'pegawai_id']],
            [['surat_tugas_id'], 'exist', 'skipOnError' => true, 'targetClass' => SuratTugas::className(), 'targetAttribute' => ['surat_tugas_id' => 'surat_tugas_id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'surat_tugas_assignee_id' => 'Id Assignee',
            'id_pegawai' => 'Id Pegawai',
            'surat_tugas_id' => 'Id Surat Tugas',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPegawai()
    {
        return $this->hasOne(Pegawai::className(), ['pegawai_id' => 'id_pegawai']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSuratTugas()
    {
        return $this->hasOne(SuratTugas::className(), ['surat_tugas_id' => 'surat_tugas_id']);
    }
    
    public function getBiayaPerjalanan()
    {
        return $this->hasMany(BiayaPerjalanan::className(), ['surat_tugas_assignee_id' => 'surat_tugas_assignee_id']);
    }
}
