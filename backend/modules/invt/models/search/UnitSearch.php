<?php

namespace backend\modules\invt\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\invt\models\Unit;
use backed\modules\invt\models\UnitCharged;

/**
 * UnitSearch represents the model behind the search form about `backend\modules\invt\models\Unit`.
 */
class UnitSearch extends Unit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unit_id', 'deleted'], 'integer'],
            [['nama', 'desc', 'deleted_by', 'deleted_at', 'updated_by', 'updated_at', 'created_by', 'created_at'], 'safe'],
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
        $query = Unit::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'nama', $this->nama]);
        $query->andWhere(['deleted'=>0]);
        return $dataProvider;
    }
}
