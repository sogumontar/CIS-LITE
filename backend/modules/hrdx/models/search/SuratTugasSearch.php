<?php

namespace backend\modules\hrdx\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\hrdx\models\SuratTugas;

/**
 * SuratTugasSearch represents the model behind the search form about `backend\modules\hrdx\models\SuratTugas`.
 */
class SuratTugasSearch extends SuratTugas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['surat_tugas_id', 'perequest', 'status', 'deleted'], 'integer'],
            [['no_surat_tugas', 'tugas', 'lokasi_tugas', 'tanggal_berangkat', 'tanggal_kembali', 'tanggal_mulai', 'tanggal_selesai', 'keterangan', 'pemberi_tugas', 'catatan', 'deleted_by', 'deleted_at', 'updated_by', 'updated_at', 'created_by', 'created_at'], 'safe'],
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
        $query = SuratTugas::find()->orderBy('surat_tugas_id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'surat_tugas_id' => $this->surat_tugas_id,
            'perequest' => $this->perequest,
            'tanggal_berangkat' => $this->tanggal_berangkat,
            'tanggal_kembali' => $this->tanggal_kembali,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'status' => $this->status,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'tugas', $this->tugas])
            ->andFilterWhere(['like', 'no_surat_tugas', $this->no_surat_tugas])
            ->andFilterWhere(['like', 'lokasi_tugas', $this->lokasi_tugas])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'pemberi_tugas', $this->pemberi_tugas])
            ->andFilterWhere(['like', 'catatan', $this->catatan])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
}
