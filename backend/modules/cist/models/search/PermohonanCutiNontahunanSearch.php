<?php

namespace backend\modules\cist\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\cist\models\PermohonanCutiNontahunan;

/**
 * PermohonanCutiNontahunanSearch represents the model behind the search form about `backend\modules\cist\models\PermohonanCutiNontahunan`.
 */
class PermohonanCutiNontahunanSearch extends PermohonanCutiNontahunan
{
    public $pegawai_nama;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permohonan_cuti_nontahunan_id', 'lama_cuti', 'kategori_id', 'status_cuti_nontahunan_id', 'pegawai_id', 'deleted'], 'integer'],
            [['pegawai_nama', 'tgl_mulai', 'tgl_akhir', 'alasan_cuti', 'pengalihan_tugas', 'deleted_at', 'deleted_by', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
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
        $query = PermohonanCutiNontahunan::find();
        $query->joinWith(['pegawai']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC, 'created_at' => SORT_DESC]],
        ]);

        $dataProvider->sort->attributes['pegawai_nama'] = [
            'asc' => ['hrdx_pegawai.nama' => SORT_ASC],
            'desc' => ['hrdx_pegawai.nama' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'permohonan_cuti_nontahunan_id' => $this->permohonan_cuti_nontahunan_id,
            'tgl_mulai' => $this->tgl_mulai,
            'tgl_akhir' => $this->tgl_akhir,
            'lama_cuti' => $this->lama_cuti,
            'kategori_id' => $this->kategori_id,
            'status_cuti_nontahunan_id' => $this->status_cuti_nontahunan_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'alasan_cuti', $this->alasan_cuti])
            ->andFilterWhere(['like', 'hrdx_pegawai.nama', $this->pegawai_nama])
            ->andFilterWhere(['like', 'pengalihan_tugas', $this->pengalihan_tugas])
            ->andFilterWhere(['like', 'deleted_by', $this->deleted_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by])
            ->andFilterWhere(['like', 'updated_by', $this->updated_by]);

        return $dataProvider;
    }
}
