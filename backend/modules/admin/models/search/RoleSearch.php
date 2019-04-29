<?php

namespace backend\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\admin\models\Role;
use backend\modules\admin\models\UserHasRole;
use backend\modules\admin\models\RoleHasMenuItem;
use backend\modules\admin\models\RoleHasTask;
use backend\modules\admin\models\RoleHasPermission;
use backend\modules\admin\models\RoleHasModule;
use backend\modules\admin\models\RoleHasController;
use backend\modules\admin\models\RoleHasApplication;
use backend\modules\admin\models\RoleHasAction;

/**
 * RoleSearch represents the model behind the search form about `backend\modules\admin\models\Role`.
 */
class RoleSearch extends Role
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'parent_id'], 'integer'],
            [['name', 'desc', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Data provider untuk left join role dan menuItem
     * @param  ActiveQuery $params  query params
     * @param  integer $menuId Menu Id
     * @return mixed
     */
    public function searchWithMenuItem($params, $menuId=null){
        //use left join
        $query = Role::find()->joinWith(['menuItems']);

        $roleHasMenuItemTableName = RoleHasMenuItem::tableName();
        if($menuId != null){
            $query = Role::find()->joinWith(['roleHasMenuItems' => function ($query) use ($menuId, $roleHasMenuItemTableName) {
                                                            $query->onCondition([$roleHasMenuItemTableName.'.menu_item_id' => $menuId]);
                                                    }]);
        }

        return $this->_getDataProvider($query, $params);
    }

    /**
     * Data provider untuk left join role dan appComponent
     * @param  ActiveQuery $params  query params
     * @param  integer $appId Application Id
     * @return mixed
     */
    public function searchWithApps($params, $appId){
        //use left join
        $query = Role::find()->joinWith(['roleHasApplications']);

        $roleHasApplicationTableName = RoleHasApplication::tableName();
        $query = Role::find()->joinWith(['roleHasApplications' => function ($query) use ($appId, $roleHasApplicationTableName){
                                                            $query->onCondition([$roleHasApplicationTableName.'.application_id' => $appId]);
                                                    }]);

        return $this->_getDataProvider($query, $params);
    }

    /**
     * Data provider untuk left join role dan appComponent
     * @param  ActiveQuery $params  query params
     * @param  integer $module&lt; Module Id
     * @return mixed
     */
    public function searchWithAppModules($params, $moduleId){
        //use left join
        $query = Role::find()->joinWith(['roleHasModules']);
        
        $roleHasModuleTableName = RoleHasModule::tableName();
        $query = Role::find()->joinWith(['roleHasModules' => function ($query) use ($moduleId, $roleHasModuleTableName){
                                                            $query->onCondition([$roleHasModuleTableName.'.module_id' => $moduleId]);
                                                    }]);

        return $this->_getDataProvider($query, $params);
    }

    /**
     * Data provider untuk left join role dan appComponent
     * @param  ActiveQuery $params  query params
     * @param  integer $controllerId 
     * @return mixed
     */
    public function searchWithAppControllers($params, $controllerId){
        //use left join
        $query = Role::find()->joinWith(['roleHasControllers']);

        $roleHasControllerTableName = RoleHasController::tableName();
        $query = Role::find()->joinWith(['roleHasControllers' => function ($query) use ($controllerId, $roleHasControllerTableName) {
                                                            $query->onCondition([$roleHasControllerTableName.'.controller_id' => $controllerId]);
                                                    }]);

        return $this->_getDataProvider($query, $params);
    }

    /**
     * Data provider untuk left join role dan appComponent
     * @param  ActiveQuery $params  query params
     * @param  integer $actionId
     * @return mixed
     */
    public function searchWithAppActions($params, $actionId){
        //use left join
        $query = Role::find()->joinWith(['roleHasActions']);

        $rhaTableName = RoleHasAction::tableName();
        $query = Role::find()->joinWith(['roleHasActions' => function ($query) use ($actionId, $rhaTableName){
                                                            $query->onCondition([$rhaTableName.'.action_id' => $actionId]);
                                                    }]);

        return $this->_getDataProvider($query, $params);
    }

    /**
     * Data provider untuk left join role dan appComponent
     * @param  ActiveQuery $params  query params
     * @param  integer $permissionId
     * @return mixed
     */
    public function searchWithAppPermissions($params, $permissionId){
        //use left join
        $query = Role::find()->joinWith(['roleHasPermissions']);

        $rhpTableName = RoleHasPermission::tableName();
        $query = Role::find()->joinWith(['roleHasPermissions' => function ($query) use ($permissionId, $rhpTableName){
                                                            $query->onCondition([$rhpTableName.'.permission_id' => $permissionId]);
                                                    }]);

        return $this->_getDataProvider($query, $params);
    }

    /**
     * Data provider untuk left join role dan user
     * @param  ActiveQuery $params  query params
     * @param  integer $userId 
     * @return mixed
     */
    public function searchWithUser($params, $userId=null){
        //use left join
        $query = Role::find()->joinWith(['users']);

        $userHasRoleTableName = UserHasRole::tableName();
        if($userId != null){
            $query = Role::find()->joinWith(['userHasRoles' => function ($query) use ($userId, $userHasRoleTableName) {
                                                            $query->onCondition([$userHasRoleTableName.'.user_id' => $userId]);
                                                    }]);
        }

        return $this->_getDataProvider($query, $params);
        
    }

    /**
     * return default data provider
     * @param  ActiveQuery $query  custom query
     * @param  array $params search params
     * @return ActiveDataProvider
     */
    private function _getDataProvider($query, $params){

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'role_id' => $this->role_id,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Role::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'role_id' => $this->role_id,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc]);

        return $dataProvider;
    }
}
