<?php

namespace backend\modules\admin\models;

use Yii;
use common\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "role_has_application".
 *
 * @property integer $role_id
 * @property integer $application_id
 *
 * @property Role $role
 * @property Application $application
 */
class RoleHasApplication extends \yii\db\ActiveRecord
{
    //behaviour to add created_at and updatet_at field with current timestamp
    public function behaviors(){
        return [
            [
                'class' => TimestampBehavior::className(),
                //'createdAtAttribute' => 'create_time',
                //'updatedAtAttribute' => 'update_time',
                // 'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                //'createdByAttribute' => 'author_id',
               //'updatedByAttribute' => 'updater_id',
            ]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sysx_role_has_application';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'application_id'], 'required'],
            [['role_id', 'application_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'application_id' => 'Application ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::className(), ['application_id' => 'application_id']);
    }
}
