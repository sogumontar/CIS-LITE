<?php

namespace backend\modules\hrdx\models;

use Yii;
use backend\modules\hrdx\models\Pegawai;

/**
 * This is the model class for table "hrdx_penerima_tugas".
 *
 * @property integer $penerima_tugas_id
 * @property integer $surat_tugas_id
 * @property integer $pegawai_id
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 */
class PenerimaTugas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hrdx_penerima_tugas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['surat_tugas_id', 'pegawai_id', 'deleted'], 'integer'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'penerima_tugas_id' => 'Penerima Tugas ID',
            'surat_tugas_id' => 'Surat Tugas ID',
            'pegawai_id' => 'Pegawai ID',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(), ["pegawai_id" => "pegawai_id"]);
    }
}
