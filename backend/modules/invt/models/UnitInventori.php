<?php

namespace backend\modules\invt\models;

use Yii;

/**
 * This is the model class for table "invt_unit_inventori".
 *
 * @property integer $unit_inventori_id
 * @property string $nama
 * @property string $desc
 * @property integer $charged_by
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 */
class UnitInventori extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invt_unit_inventori';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['charged_by', 'deleted'], 'integer'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['nama'], 'string', 'max' => 150],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'unit_inventori_id' => 'Unit Inventori ID',
            'nama' => 'Nama',
            'desc' => 'Desc',
            'charged_by' => 'Charged By',
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
