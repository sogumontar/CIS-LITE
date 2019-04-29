<?php

namespace backend\modules\hrdx\models;

use Yii;
use backend\modules\hrdx\models\JenisFasilitas;

/**
 * This is the model class for table "hrdx_fasilitas_perjalanan".
 *
 * @property integer $fasilitas_perjalanan_id
 * @property integer $surat_tugas_id
 * @property integer $jenis_fasilitas_id
 * @property string $keterangan
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 */
class FasilitasPerjalanan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hrdx_fasilitas_perjalanan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['surat_tugas_id', 'jenis_fasilitas_id', 'deleted'], 'integer'],
            [['keterangan'], 'string'],
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
            'fasilitas_perjalanan_id' => 'Fasilitas Perjalanan ID',
            'surat_tugas_id' => 'Surat Tugas ID',
            'jenis_fasilitas_id' => 'Jenis Fasilitas ID',
            'keterangan' => 'Keterangan',
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
    public function getJenisFasilitas()
    {
        return $this->hasOne(JenisFasilitas::className(), ["jenis_fasilitas_id" => "jenis_fasilitas_id"]);
    }
}
