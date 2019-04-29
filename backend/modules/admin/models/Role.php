<?php

namespace backend\modules\admin\models;

use Yii;
use common\behaviors\TimestampBehavior;
use common\behaviors\BlameableBehavior;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "role".
 *
 * @property integer $role_id
 * @property integer $parent_id
 * @property string $name
 * @property string $desc
 * @property string $created_at
 * @property string $updated_at
 *
 * @property RoleHasAction[] $roleHasActions
 * @property Action[] $actions
 * @property RoleHasApplication[] $roleHasApplications
 * @property Application[] $applications
 * @property RoleHasController[] $roleHasControllers
 * @property Controller[] $controllers
 * @property RoleHasMenuItem[] $roleHasMenuItems
 * @property MenuItem[] $menuItems
 * @property RoleHasModule[] $roleHasModules
 * @property Module[] $modules
 * @property RoleHasPermission[] $roleHasPermissions
 * @property Permission[] $permissions
 * @property RoleHasTask[] $roleHasTasks
 * @property Task[] $tasks
 * @property UserHasRole[] $userHasRoles
 * @property User[] $users
 */
class Role extends \yii\db\ActiveRecord
{
    // public $childs;

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
        return 'sysx_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['desc'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'desc' => 'Desc',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

     public function getChildDataProvider(){
        $query = Role::find();

        $query->andFilterWhere(['parent_id' => $this->role_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
    /**
     * parent magic getter
     * @return parent
     */
    public function getParent(){
        return $this->find()
                    ->where('role_id = :pid', [':pid' => $this->parent_id])
                    ->one();
    }

    /**
     * childs magic getter
     * @return childs
     */
    public function getChilds(){
        return $this->find()
                    ->where('parent_id = :pid', [':pid' => $this->role_id])
                    ->all();
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleHasActions()
    {
        return $this->hasMany(RoleHasAction::className(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActions()
    {
        return $this->hasMany(Action::className(), ['action_id' => 'action_id'])->viaTable(RoleHasAction::tableName(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleHasApplications()
    {
        return $this->hasMany(RoleHasApplication::className(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::className(), ['application_id' => 'application_id'])->viaTable(RoleHasApplication::tableName(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleHasControllers()
    {
        return $this->hasMany(RoleHasController::className(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getControllers()
    {
        return $this->hasMany(Controller::className(), ['controller_id' => 'controller_id'])->viaTable(RoleHasController::tableName(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleHasMenuItems()
    {
        return $this->hasMany(RoleHasMenuItem::className(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['menu_item_id' => 'menu_item_id'])->viaTable(RoleHasMenuItem::tableName(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleHasModules()
    {
        return $this->hasMany(RoleHasModule::className(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModules()
    {
        return $this->hasMany(Module::className(), ['module_id' => 'module_id'])->viaTable(RoleHasModule::tableName(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleHasPermissions()
    {
        return $this->hasMany(RoleHasPermission::className(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasMany(Permission::className(), ['permission_id' => 'permission_id'])->viaTable(RoleHasPermission::tableName(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleHasTasks()
    {
        return $this->hasMany(RoleHasTask::className(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['task_id' => 'task_id'])->viaTable(RoleHasTask::tableName(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHasRoles()
    {
        return $this->hasMany(UserHasRole::className(), ['role_id' => 'role_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {   
        return $this->hasMany(User::className(), ['user_id' => 'user_id'])->viaTable(UserHasRole::tableName(), ['role_id' => 'role_id']);
    }
}
