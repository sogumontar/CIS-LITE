<?php

namespace backend\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\admin\models\AuthenticationMethod;

/**
 * AuthenticationMethodSearch represents the model behind the search form about `backend\modules\admin\models\AuthenticationMethod`.
 */
class AuthenticationMethodSearch extends AuthenticationMethod
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['authentication_method_id'], 'integer'],
            [['name', 'server_address', 'authentication_string', 'desc', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'safe'],
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
    public function search($params)
    {
        $query = AuthenticationMethod::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'authentication_method_id' => $this->authentication_method_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'server_address', $this->server_address])
            ->andFilterWhere(['like', 'authentication_string', $this->authentication_string])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
