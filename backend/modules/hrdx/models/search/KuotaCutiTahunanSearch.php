<?php

namespace backend\modules\hrdx\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\hrdx\models\KuotaCutiTahunan;

/**
 * KuotaCutiTahunanSearch represents the model behind the search form about `backend\modules\hrdx\models\KuotaCutiTahunan`.
 */
class KuotaCutiTahunanSearch extends KuotaCutiTahunan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kuota_cuti_tahunan_id', 'deleted'], 'integer'],
            [['lama_bekerja', 'kuota', 'deleted_at', 'deleted_by', 'updated_at', 'updated_by', 'created_at', 'created_by'], 'safe'],
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
        $query = KuotaCutiTahunan::find();

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
            'kuota_cuti_tahunan_id' => $this->kuota_cuti_tahunan_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'lama_bekerja', $this->lama_bekerja])
            ->andFilterWhere(['like', 'kuota', $this->kuota])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
}
