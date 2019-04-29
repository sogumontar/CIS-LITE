<?php

namespace backend\modules\hrdx\models;

use Yii;

/**
 * This is the model class for table "hrdx_pegawai_struktur_jabatan".
 *
 * @property integer $pegawai_struktur_jabatan_id
 * @property integer $pegawai_id
 * @property integer $struktur_jabatan_id
 * @property integer $aktif
 * @property string $start_date
 * @property string $end_date
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 */
class PegawaiStrukturJabatan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hrdx_pegawai_struktur_jabatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pegawai_id', 'struktur_jabatan_id', 'aktif', 'deleted'], 'integer'],
            [['start_date', 'end_date', 'deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pegawai_struktur_jabatan_id' => 'Pegawai Struktur Jabatan ID',
            'pegawai_id' => 'Pegawai ID',
            'struktur_jabatan_id' => 'Struktur Jabatan ID',
            'aktif' => 'Aktif',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    public function getPegawai()
    {
        return $this->hasOne(Pegawai::className(), ["pegawai_id" => "pegawai_id"]);
    }

    public function getStrukturJabatan()
    {
        return $this->hasOne(StrukturJabatan::className(), ["struktur_jabatan_id" => "struktur_jabatan_id"]);
    }
}
