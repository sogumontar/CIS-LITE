<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use backend\modules\admin\models\Role;
use backend\modules\admin\models\AuthenticationMethod;
use backend\modules\admin\models\Log;
use backend\modules\admin\models\Profile;
use backend\modules\admin\models\UserHasRole;
use backend\modules\admin\models\UserConfig;
use backend\modules\admin\models\Workgroup;
use backend\modules\admin\models\UserHasWorkgroup;

/**
 * User model
 *
 * @property integer $user_id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;
    const IDENTITY_CACHE_KEY = 'IDKEY_AFASDFCVA_2342_ASDF';
    private $menuCache = null;

    // public function beforeSave($insert)
    // {
    //     if (parent::beforeSave($insert)) {
    //         if ($this->isNewRecord) {
    //             $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
    //         }
    //         return true;
    //     }
    //     return false;
    // }

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
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
      * @inheritdoc
      */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        //Cache Identity on production environment
        if(YII_ENV_PROD){
            $session = Yii::$app->getSession();
            $cachedIdentity = $session->get(self::IDENTITY_CACHE_KEY.$id);
            
            if($cachedIdentity){
                return $cachedIdentity;
            }
            
            $identity = static::find()
                       ->with(['profile', 'roles'])
                       ->where(['user_id' => $id, 'status' => self::STATUS_ACTIVE])
                       ->one();
            $session->set(self::IDENTITY_CACHE_KEY.$id, $identity);

            return $identity;

        }
        return static::find()
                       ->with(['roles'])
                       ->where(['user_id' => $id, 'status' => self::STATUS_ACTIVE])
                       ->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        // return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
        return static::find()
                       ->with(['authenticationMethod'])
                       ->where(['username' => $username, 'status' => self::STATUS_ACTIVE])
                       ->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */


    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
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
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function setMenuCache($menu){
        $this->menuCache = $menu;
    }

    public function getMenuCache(){
        return $this->menuCache;
    }

    public function resetMenuCache(){
        $this->menuCache == null;
    }

    public function isMenuCacheExist(){
        return isset($this->menuCache);
    }

    /**relation **/
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
