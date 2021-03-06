<?php

namespace backend\modules\mref\models;

use Yii;

use common\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "mref_r_gbk".
 *
 * @property integer $gbk_id
 * @property string $nama
 * @property string $desc
 * @property string $created_at
 * @property string $updated_at
 * @property string $created_by
 * @property string $updated_by
 */
class Gbk extends \yii\db\ActiveRecord
{

    /**
     * behaviour to add created_at and updatet_at field with current datetime (timestamp)
     * and created_by and updated_by field with current user id (blameable)
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

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mref_r_gbk';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['desc'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['nama', 'created_by', 'updated_by'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gbk_id' => 'Gbk ID',
            'nama' => 'Nama',
            'desc' => 'Desc',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
