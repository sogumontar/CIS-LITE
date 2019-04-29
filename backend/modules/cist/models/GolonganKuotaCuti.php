<?php

namespace backend\modules\cist\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "cist_golongan_kuota_cuti".
 *
 * @property integer $golongan_kuota_cuti_id
 * @property string $nama
 * @property integer $min_tahun_kerja
 * @property integer $max_tahun_kerja
 * @property integer $kuota
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 */
class GolonganKuotaCuti extends \yii\db\ActiveRecord
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
        return 'cist_golongan_kuota_cuti';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama_golongan', 'min_tahun_kerja', 'max_tahun_kerja', 'kuota'], 'required'],
            [['min_tahun_kerja', 'max_tahun_kerja', 'kuota', 'deleted'], 'integer'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['nama_golongan'], 'string', 'max' => 500],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'golongan_kuota_cuti_id' => 'Golongan Kuota Cuti ID',
            'nama_golongan' => 'Nama Golongan',
            'min_tahun_kerja' => 'Min Tahun Kerja',
            'max_tahun_kerja' => 'Max Tahun Kerja',
            'kuota' => 'Jumlah Kuota',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
