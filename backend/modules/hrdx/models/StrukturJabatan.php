<?php

namespace backend\modules\hrdx\models;

use Yii;

/**
 * This is the model class for table "hrdx_struktur_jabatan".
 *
 * @property integer $struktur_jabatan_id
 * @property integer $kategori_jabatan_id
 * @property string $nama_jabatan
 * @property integer $atasan_id
 * @property string $deskripsi
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 */
class StrukturJabatan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hrdx_struktur_jabatan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kategori_jabatan_id', 'atasan_id', 'deleted'], 'integer'],
            [['deskripsi'], 'string'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['nama_jabatan'], 'string', 'max' => 150],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'struktur_jabatan_id' => 'Struktur Jabatan ID',
            'kategori_jabatan_id' => 'Kategori Jabatan ID',
            'nama_jabatan' => 'Nama Jabatan',
            'atasan_id' => 'Atasan ID',
            'deskripsi' => 'Deskripsi',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }
}
