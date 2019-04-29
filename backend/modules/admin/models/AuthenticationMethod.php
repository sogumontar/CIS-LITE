<?php

namespace backend\modules\admin\models;

use Yii;

use common\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "authentication_method".
 *
 * @property integer $authentication_method_id
 * @property string $name
 * @property string $server_address
 * @property string $authentication_string
 * @property string $desc
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User[] $users
 */
class AuthenticationMethod extends \yii\db\ActiveRecord
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
        return 'sysx_authentication_method';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at', 'redirected', 'redirect_to'], 'safe'],
            [['name', 'server_address'], 'string', 'max' => 45],
            [['authentication_string', 'desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'authentication_method_id' => 'Authentication Method ID',
            'name' => 'Name',
            'server_address' => 'Server Address',
            'authentication_string' => 'Authentication String',
            'desc' => 'Desc',
            'redirected' => 'Redirect Auth',
            'redirect_to' => 'Redirect Auth To',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['authentication_method_id' => 'authentication_method_id']);
    }
}
