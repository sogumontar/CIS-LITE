<?php

namespace backend\modules\hrdx\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\hrdx\models\RiwayatPendidikan;

/**
 * RiwayatPendidikanSearch represents the model behind the search form about `backend\modules\hrdx\models\RiwayatPendidikan`.
 */
class RiwayatPendidikanSearch extends RiwayatPendidikan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['riwayat_pendidikan_id', 'jenjang_id', 'pegawai_id', 'deleted', 'profile_id'], 'integer'],
            [['universitas', 'jurusan', 'thn_mulai', 'thn_selesai', 'ipk', 'gelar', 'judul_ta', 'website', 'deleted_at', 'deleted_by', 'created_at', 'updated_at', 'created_by', 'updated_by', 'id_old', 'jenjang'], 'safe'],
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
        $query = RiwayatPendidikan::find();

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
            'riwayat_pendidikan_id' => $this->riwayat_pendidikan_id,
            'jenjang_id' => $this->jenjang_id,
            'pegawai_id' => $this->pegawai_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'profile_id' => $this->profile_id,
        ]);

        $query->andFilterWhere(['like', 'universitas', $this->universitas])
            ->andFilterWhere(['like', 'jurusan', $this->jurusan])
            ->andFilterWhere(['like', 'thn_mulai', $this->thn_mulai])
            ->andFilterWhere(['like', 'thn_selesai', $this->thn_selesai])
            ->andFilterWhere(['like', 'ipk', $this->ipk])
            ->andFilterWhere(['like', 'gelar', $this->gelar])
            ->andFilterWhere(['like', 'judul_ta', $this->judul_ta])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'id_old', $this->id_old])
            ->andFilterWhere(['like', 'jenjang', $this->jenjang]);

        return $dataProvider;
    }
}
