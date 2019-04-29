<?php

namespace backend\modules\cist\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\cist\models\WaktuGenerateKuotaCuti;

/**
 * WaktuGenerateKuotaCutiSearch represents the model behind the search form about `backend\modules\cist\models\WaktuGenerateKuotaCuti`.
 */
class WaktuGenerateKuotaCutiSearch extends WaktuGenerateKuotaCuti
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['waktu_generate_kuota_cuti_id', 'deleted'], 'integer'],
            [['waktu_generate_terakhir', 'deleted_at', 'deleted_by', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
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
        $query = WaktuGenerateKuotaCuti::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'waktu_generate_kuota_cuti_id' => $this->waktu_generate_kuota_cuti_id,
            'waktu_generate_terakhir' => $this->waktu_generate_terakhir,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
