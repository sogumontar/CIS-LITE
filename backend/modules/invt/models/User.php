<?php

namespace backend\modules\invt\models;

use Yii;
/**
 * This is the model class for table "sysx_user".
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
 * @property string $created_by
 * @property string $updated_by
 * @property integer $deleted
 * @property string $deleted_at
 * @property string $deleted_by
 */
class User extends \yii\db\ActiveRecord
{
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
            [['profile_id', 'authentication_method_id', 'status', 'deleted'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'email'], 'required'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key', 'deleted_by'], 'string', 'max' => 32],
            [['created_by', 'updated_by'], 'string', 'max' => 45]
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
            'authentication_method_id' => 'Authentication Method ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted' => 'Deleted',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getUnits()
    {
        return $this->hasMany(Unit::className(),['unit_id'=>'unit_id'])->viaTable(UnitCharged::tableName(),['user_id'=>'user_id']);
    }
}
