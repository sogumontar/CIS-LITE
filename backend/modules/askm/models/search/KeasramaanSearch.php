<?php

namespace backend\modules\askm\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\askm\models\Keasramaan;

/**
 * KeasramaanSearch represents the model behind the search form about `backend\modules\askm\models\Keasramaan`.
 */
class KeasramaanSearch extends Keasramaan
{
    public $nama_keasramaan;
    public $foto_keasramaan;
    public $telepon_keasramaan;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keasramaan_id', 'asrama_id', 'pegawai_id', 'deleted'], 'integer'],
            [['telepon_keasramaan', 'no_hp', 'email', 'deleted_at', 'deleted_by', 'created_at', 'created_by', 'updated_at', 'updated_by', 'nama_keasramaan', 'foto_keasramaan'], 'safe'],
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
        $query = Keasramaan::find();
        $query->joinWith(['pegawai']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['nama_keasramaan'] = [
            'asc' => ['hrdx_pegawai.nama' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nama' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['telepon_keasramaan'] = [
            'asc' => ['hrdx_pegawai.telepon' => SORT_ASC],
            'desc' => ['hrdx_pegawai.telepon' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'keasramaan_id' => $this->keasramaan_id,
            'asrama_id' => $this->asrama_id,
            'pegawai_id' => $this->pegawai_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'no_hp', $this->no_hp])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->nama_keasramaan])
            ->andFilterWhere(['like', 'hrdx_pegawai.telepon', $this->telepon_keasramaan])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['not', ['askm_keasramaan.deleted' => 1]]);

        return $dataProvider;
    }
}
