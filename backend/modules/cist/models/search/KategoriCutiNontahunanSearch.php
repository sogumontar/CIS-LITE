<?php

namespace backend\modules\cist\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\cist\models\KategoriCutiNontahunan;

/**
 * KategoriCutiNontahunanSearch represents the model behind the search form about `backend\modules\cist\models\KategoriCutiNontahunan`.
 */
class KategoriCutiNontahunanSearch extends KategoriCutiNontahunan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kategori_cuti_nontahunan_id', 'lama_pelaksanaan', 'satuan', 'deleted'], 'integer'],
            [['name', 'deleted_at', 'deleted_by', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
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
        $query = KategoriCutiNontahunan::find();

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
            'kategori_cuti_nontahunan_id' => $this->kategori_cuti_nontahunan_id,
            'lama_pelaksanaan' => $this->lama_pelaksanaan,
            'satuan' => $this->satuan,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
