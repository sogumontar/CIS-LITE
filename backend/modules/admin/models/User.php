<?php

namespace backend\modules\admin\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;

/**
 * This is the model class for table "user".
 *
 * @property integer $user_id
 * @property integer $profile_id
 * @property integer $authentication_method_id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Log[] $logs
 * @property Profile $profile
 * @property AuthenticationMethod $authenticationMethod
 * @property UserHasRole[] $userHasRoles
 * @property Role[] $roles
 */
class User extends \yii\db\ActiveRecord
{

    //behaviour to add created_at and updatet_at field with current timestamp
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
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sysx_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profile_id', 'authentication_method_id', 'status'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'email'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'profile_id' => 'Profile ID',
            'authentication_method_id' => 'Authentication Method',
            'username' => 'Username',
            'sysx_key' => 'SystemX Key',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelkomSsoUser()
    {
        return $this->hasOne(TelkomSsoUser::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthenticationMethod()
    {
        return $this->hasOne(AuthenticationMethod::className(), ['authentication_method_id' => 'authentication_method_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasRoles()
    {
        return $this->hasMany(UserHasRole::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Role::className(), ['role_id' => 'role_id'])->viaTable(UserHasRole::tableName(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasWorkgroups()
    {
        return $this->hasMany(UserHasWorkgroup::className(), ['user_id' => 'user_id']);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkgroups()
    {
        return $this->hasMany(Workgroup::className(), ['workgroup_id' => 'workgroup_id'])->viaTable(UserHasWorkgroup::tableName(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserConfigs()
    {
        return $this->hasMany(UserConfig::className(), ['user_id' => 'user_id']);
    }
}
