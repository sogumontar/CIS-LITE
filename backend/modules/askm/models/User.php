<?php

namespace backend\modules\askm\models;

use Yii;

use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use common\behaviors\DeleteBehavior;

/**
 * This is the model class for table "sysx_user".
 *
 * @property integer $user_id
 * @property integer $profile_id
 * @property string $sysx_key
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
 *
 * @property ArspArsip[] $arspArsips
 * @property ArtkPost[] $artkPosts
 * @property Dim[] $Dims
 * @property Pegawai[] $Pegawais
 * @property InvtPelaporanBarangRusak[] $invtPelaporanBarangRusaks
 * @property InvtPeminjamanBarang[] $invtPeminjamanBarangs
 * @property InvtPeminjamanBarang[] $invtPeminjamanBarangs0
 * @property InvtUnitCharged[] $invtUnitChargeds
 * @property PrklKrsReview[] $prklKrsReviews
 * @property RprtComplaint[] $rprtComplaints
 * @property RprtResponse[] $rprtResponses
 * @property RprtUserHasBagian[] $rprtUserHasBagians
 * @property SchdEventInvitee[] $schdEventInvitees
 * @property SrvyKuesionerJawabanPeserta[] $srvyKuesionerJawabanPesertas
 * @property SysxLog[] $sysxLogs
 * @property SysxTelkomSsoUser[] $sysxTelkomSsoUsers
 * @property SysxAuthenticationMethod $authenticationMethod
 * @property SysxProfile $profile
 * @property SysxUserConfig[] $sysxUserConfigs
 * @property SysxUserHasRole[] $sysxUserHasRoles
 * @property SysxRole[] $roles
 * @property SysxUserHasWorkgroup[] $sysxUserHasWorkgroups
 * @property SysxWorkgroup[] $workgroups
 * @property TmbhPengumuman[] $tmbhPengumumen
 */
class User extends \yii\db\ActiveRecord
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
            [['sysx_key', 'auth_key', 'deleted_by'], 'string', 'max' => 32],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['created_by', 'updated_by'], 'string', 'max' => 45],
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
            'sysx_key' => 'Sysx Key',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDims()
    {
        return $this->hasMany(Dim::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawais()
    {
        return $this->hasMany(Pegawai::className(), ['user_id' => 'user_id']);
    }

}
