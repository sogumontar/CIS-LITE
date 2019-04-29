<?php

namespace backend\modules\rppx\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\rppx\models\PenugasanPengajaran;

/**
 * PenugasanPengajaranSearch represents the model behind the search form about `backend\modules\rppx\models\PenugasanPengajaran`.
 */
class PenugasanPengajaranSearch extends PenugasanPengajaran
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['penugasan_pengajaran_id', 'pengajaran_id', 'pegawai_id', 'role_pengajar_id', 'is_fulltime', 'deleted'], 'integer'],
            [['start_date', 'end_date', 'deleted_by', 'deleted_at', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'safe'],
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
        $query = PenugasanPengajaran::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'penugasan_pengajaran_id' => $this->penugasan_pengajaran_id,
            'pengajaran_id' => $this->pengajaran_id,
            'pegawai_id' => $this->pegawai_id,
            'role_pengajar_id' => $this->role_pengajar_id,
            'is_fulltime' => $this->is_fulltime,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
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
