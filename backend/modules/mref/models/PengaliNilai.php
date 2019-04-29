<?php

namespace backend\modules\mref\models;

use Yii;

use common\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "mref_r_agama".
 *
 * @property integer $agama_id
 * @property string $nama
 * @property string $desc
 * @property integer $deleted
 * @property string $created_by
 * @property string $created_at
 * @property string $updated_by
 * @property string $updated_at
 */
class PengaliNilai extends \yii\db\ActiveRecord
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
        return 'mref_r_pengali_nilai';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['desc'], 'string'],
            [['pengali'], 'float'],
            [['deleted'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['nilai'], 'string', 'max' => 3],
            [['created_by', 'updated_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pengali_nilai_id' => 'Pengali Nilai ID',
            'nilai' => 'Nilai',
            'pengali'=>'Pengali',
            'desc' => 'Desc',
            'deleted' => 'Deleted',
            'deleted_by'=>'Deleted By',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }
}
