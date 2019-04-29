<?php

namespace backend\modules\hrdx\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "hrdx_r_kuota_cuti_tahunan".
 *
 * @property integer $kuota_cuti_tahunan_id
 * @property string $lama_bekerja
 * @property string $kuota
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 * @property string $created_by
 */
class KuotaCutiTahunan extends \yii\db\ActiveRecord
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
        return 'hrdx_r_kuota_cuti_tahunan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lama_bekerja', 'kuota'], 'required'],
            [['deleted'], 'integer'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['satuan'], 'string', 'max' => 20],
            [['lama_bekerja', 'kuota'], 'string', 'max' => 20],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'kuota_cuti_tahunan_id' => 'Kuota Cuti Tahunan ID',
            'lama_bekerja' => 'Lama Bekerja',
            'kuota' => 'Kuota',
            'satuan' => 'Satuan',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }
}
