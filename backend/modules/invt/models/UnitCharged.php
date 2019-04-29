<?php

namespace backend\modules\invt\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
/**
 * This is the model class for table "invt_unit_charged".
 *
 * @property integer $unit_charged_id
 * @property integer $unit_id
 * @property integer $user_id
 * @property integer $deleted
 * @property string $deleted_by
 * @property string $deleted_at
 * @property string $updated_by
 * @property string $updated_at
 * @property string $created_by
 * @property string $created_at
 */
class UnitCharged extends \yii\db\ActiveRecord
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
            ]
        ];
    }

    public static function tableName()
    {
        return 'invt_unit_charged';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unit_id', 'user_id',], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['updated_by', 'created_by'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'unit_charged_id' => 'Unit Charged ID',
            'unit_id' => 'Unit ID',
            'user_id' => 'User ID',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id'=>'user_id']);
    }
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['unit_id'=>'unit_id']);
    }

    public static function getUnitbyUser($user_id)
    {
        return UnitCharged::find()
                            ->select('unit_id')
                            ->where('user_id=:ui',['ui'=>$user_id])
                            ->all();
    }
}
