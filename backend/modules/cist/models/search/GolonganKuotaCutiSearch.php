<?php

namespace backend\modules\cist\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\cist\models\GolonganKuotaCuti;

/**
 * GolonganKuotaCutiSearch represents the model behind the search form about `backend\modules\cist\models\GolonganKuotaCuti`.
 */
class GolonganKuotaCutiSearch extends GolonganKuotaCuti
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['golongan_kuota_cuti_id', 'min_tahun_kerja', 'max_tahun_kerja', 'kuota', 'deleted'], 'integer'],
            [['nama_golongan', 'deleted_at', 'deleted_by', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
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
        $query = GolonganKuotaCuti::find();

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
            'golongan_kuota_cuti_id' => $this->golongan_kuota_cuti_id,
            'min_tahun_kerja' => $this->min_tahun_kerja,
            'max_tahun_kerja' => $this->max_tahun_kerja,
            'kuota' => $this->kuota,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'nama_golongan', $this->nama_golongan])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
