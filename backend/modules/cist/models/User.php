<?php

namespace backend\modules\cist\models;

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
 * @property CistSuratTugas[] $SuratTugas
 * @property DimxDim[] $dimxDims
 * @property HrdxPegawai[] $hrdxPegawais
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
            [['authentication_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => SysxAuthenticationMethod::className(), 'targetAttribute' => ['authentication_method_id' => 'authentication_method_id']],
            [['profile_id'], 'exist', 'skipOnError' => true, 'targetClass' => SysxProfile::className(), 'targetAttribute' => ['profile_id' => 'profile_id']]
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
    public function getArspArsips()
    {
        return $this->hasMany(Arsip::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtkPosts()
    {
        return $this->hasMany(Post::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSuratTugas()
    {
        return $this->hasMany(SuratTugas::className(), ['perequest' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimxDims()
    {
        return $this->hasMany(Dim::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawais()
    {
        return $this->hasOne(Pegawai::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvtPelaporanBarangRusaks()
    {
        return $this->hasMany(PelaporanBarangRusak::className(), ['pelapor' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvtPeminjamanBarangs()
    {
        return $this->hasMany(PeminjamanBarang::className(), ['approved_by' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvtPeminjamanBarangs0()
    {
        return $this->hasMany(PeminjamanBarang::className(), ['oleh' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvtUnitChargeds()
    {
        return $this->hasMany(UnitCharged::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrklKrsReviews()
    {
        return $this->hasMany(KrsReview::className(), ['comment_by' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRprtComplaints()
    {
        return $this->hasMany(Complaint::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRprtResponses()
    {
        return $this->hasMany(Response::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRprtUserHasBagians()
    {
        return $this->hasMany(UserHasBagian::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchdEventInvitees()
    {
        return $this->hasMany(EventInvitee::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSrvyKuesionerJawabanPesertas()
    {
        return $this->hasMany(KuesionerJawabanPeserta::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSysxLogs()
    {
        return $this->hasMany(Log::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSysxTelkomSsoUsers()
    {
        return $this->hasMany(TelkomSsoUser::className(), ['user_id' => 'user_id']);
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
    public function getProfile()
    {
        return $this->hasOne(SysxProfile::className(), ['profile_id' => 'profile_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSysxUserConfigs()
    {
        return $this->hasMany(SysxUserConfig::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSysxUserHasRoles()
    {
        return $this->hasMany(SysxUserHasRole::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(SysxRole::className(), ['role_id' => 'role_id'])->viaTable('sysx_user_has_role', ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSysxUserHasWorkgroups()
    {
        return $this->hasMany(SysxUserHasWorkgroup::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWorkgroups()
    {
        return $this->hasMany(SysxWorkgroup::className(), ['workgroup_id' => 'workgroup_id'])->viaTable('sysx_user_has_workgroup', ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTmbhPengumumen()
    {
        return $this->hasMany(TmbhPengumuman::className(), ['owner' => 'user_id']);
    }
}
