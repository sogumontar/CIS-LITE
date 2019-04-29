<?php

namespace backend\modules\askm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\askm\models\DimPelanggaran;

/**
 * DimPelanggaranSearch represents the model behind the search form about `backend\modules\askm\models\DimPelanggaran`.
 */
class DimPelanggaranSearch extends DimPelanggaran
{
    public $pelanggaran_name;
    public $pelanggaran_poin;
    public $pembinaan;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pelanggaran_id', 'status_pelanggaran', 'pembinaan_id', 'penilaian_id', 'poin_id', 'deleted'], 'integer'],
            [['pembinaan', 'pelanggaran_poin', 'pelanggaran_name', 'desc_pembinaan', 'desc_pelanggaran', 'tanggal', 'deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
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
        $query = DimPelanggaran::find();
        $query->joinWith(['pembinaan', 'poin']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['pelanggaran_name'] = [
            'asc' => ['askm_poin_pelanggaran.name' => SORT_ASC],
            'desc' => ['askm_poin_pelanggaran.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['pelanggaran_poin'] = [
            'asc' => ['askm_poin_pelanggaran.poin' => SORT_ASC],
            'desc' => ['askm_poin_pelanggaran.poin' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['pembinaan'] = [
            'asc' => ['askm_pembinaan.name' => SORT_ASC],
            'desc' => ['askm_poin_pelanggaran.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'pelanggaran_id' => $this->pelanggaran_id,
            'status_pelanggaran' => $this->status_pelanggaran,
            'askm_pembinaan.pembinaan_id' => $this->pembinaan_id,
            'penilaian_id' => $this->penilaian_id,
            'askm_poin_pelanggaran.poin_id' => $this->poin_id,
            'askm_poin_pelanggaran.poin' => $this->pelanggaran_poin,
            'tanggal' => $this->tanggal,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'pelanggaran_name', $this->pelanggaran_name])
            ->andFilterWhere(['like', 'pembinaan', $this->pembinaan])
            ->andFilterWhere(['like', 'desc_pembinaan', $this->desc_pembinaan])
            ->andFilterWhere(['like', 'desc_pelanggaran', $this->desc_pelanggaran])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['not', ['askm_poin_pelanggaran.deleted' => 1]]);

        return $dataProvider;
    }
}
