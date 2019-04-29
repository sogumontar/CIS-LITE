<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "mref_r_sem_ta".
 *
 * @property integer $sem_ta_id
 * @property integer $sem_ta
 * @property string $desc
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 */
class SemTa extends \yii\db\ActiveRecord
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
        return 'mref_r_sem_ta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sem_ta', 'deleted'], 'integer'],
            [['desc'], 'string'],
            [['deleted_at', 'created_at', 'updated_at'], 'safe'],
            [['deleted_by', 'created_by', 'updated_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sem_ta_id' => 'Sem Ta ID',
            'sem_ta' => 'Sem Ta',
            'desc' => 'Desc',
            'deleted' => 'Deleted',
            'deleted_by' => 'Deleted By',
            'deleted_at' => 'Deleted At',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
