<?php

namespace backend\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\admin\models\User;
use backend\modules\admin\models\UserHasWorkgroup;
/**
 * UserSearch represents the model behind the search form about `backend\modules\admin\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'profile_id', 'authentication_method_id', 'status'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'created_at', 'updated_at'], 'safe'],
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

    public function searchWithTelkomSso($params){
        $query = User::find()->with('telkomSsoUser')->where('status = 1');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'username', $this->username]);

        return $dataProvider;
    }

    /**
     * Data provider untuk left join user dan workgroup
     * @param  ActiveQuery $params  query params
     * @param  integer $user_id 
     * @return mixed
     */
    public function searchWithWorkgroups($params, $userId, $assignMember=false){
        //use left join
        $query = User::find()->joinWith(['workgroups']);
        $userHasWorkgroupTableName = UserHasWorkgroup::tableName();

        if($assignMember){
            $query = User::find()->joinWith(['userHasWorkgroups' => function ($query) use ($userId, $userHasWorkgroupTableName) {
                                                        $query->onCondition([$userHasWorkgroupTableName.'.workgroup_id' => $userId]);
                                                }]);
        }else{
            $query = User::find()->innerJoinWith(['userHasWorkgroups' => function ($query) use ($userId, $userHasWorkgroupTableName) {
                                                        $query->onCondition([$userHasWorkgroupTableName.'.workgroup_id' => $userId]);
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
            'user_id' => $this->user_id,
            'profile_id' => $this->profile_id,
            'authentication_method_id' => $this->authentication_method_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email]);

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
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'profile_id' => $this->profile_id,
            'authentication_method_id' => $this->authentication_method_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
