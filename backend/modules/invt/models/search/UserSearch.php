<?php

namespace backend\modules\invt\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\invt\models\User;

/**
 * UserSearch represents the model behind the search form about `backend\modules\invt\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'profile_id', 'authentication_method_id', 'status', 'deleted'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at', 'deleted_by'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $_query)
    {
         $query = User::find();
        if($_query != null){
           $query = $_query;
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['like', $this->tableName().'.username', $this->username])
            ->andFilterWhere(['like', $this->tableName().'.email', $this->email]);
        $query->andWhere([$this->tableName().'.deleted'=>0]);
        return $dataProvider;
    }
}
