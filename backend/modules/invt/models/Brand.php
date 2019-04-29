<?php

namespace backend\modules\invt\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "invt_r_brand".
 *
 * @property integer $brand_id
 * @property string $nama
 * @property string $desc
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
            [
                'class' => BlameableBehavior::className(),
            ],
            [
                'class' => DeleteBehavior::className(),
                'hardDeleteTaskName' => 'HardDeleteDB', //default
                'enableSoftDelete' => true, //default, set false jika behavior ini ingin di bypass. cth, untuk proses debugging
            ]
        ];
    }
    public static function tableName()
    {
        return 'invt_r_brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['deleted'], 'integer'],
            [['deleted_at', 'updated_at', 'created_at'], 'safe'],
            [['nama'], 'string', 'max' => 200],
            [['deleted_by', 'updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'brand_id' => 'Brand ID',
            'nama' => 'Brand',
            'desc' => 'Deskripsi',
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
